<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\ComplaintStatusHistory;
use App\Models\PortalNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::where('student_id', $request->user()->id)
            ->with(['category', 'assignee']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $query->where(fn ($q) => $q->where('reference_no', 'like', $term)->orWhere('title', 'like', $term));
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();
        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        $categories = ComplaintCategory::where('is_active', true)->orderBy('name')->get();
        return view('complaints.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:4', 'max:180'],
            'description' => ['required', 'string', 'min:10', 'max:10000'],
            'category_id' => ['required', 'exists:complaint_categories,id'],
            'incident_date' => ['nullable', 'date', 'before_or_equal:today'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $category = ComplaintCategory::where('is_active', true)->findOrFail($data['category_id']);
        $assignee = User::where('role', $category->assigned_role)->where('is_active', true)->first();

        if (!$assignee) {
            return back()->withInput()->withErrors(['category_id' => 'No active administrator is configured for this category.']);
        }

        $complaint = DB::transaction(function () use ($request, $data, $category, $assignee) {
            $complaint = Complaint::create([
                'reference_no' => 'BAUST-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'student_id' => $request->user()->id,
                'category_id' => $category->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'incident_date' => $data['incident_date'] ?? null,
                'status' => 'Pending',
                'assigned_to' => $assignee->id,
                'last_updated_at' => now(),
            ]);

            ComplaintStatusHistory::create([
                'complaint_id' => $complaint->id,
                'previous_status' => null,
                'new_status' => 'Pending',
                'administrator_id' => null,
                'remark' => 'Complaint submitted.',
            ]);

            PortalNotification::create([
                'recipient_id' => $assignee->id,
                'complaint_id' => $complaint->id,
                'message' => 'A new complaint '.$complaint->reference_no.' has been assigned to you.',
            ]);

            foreach ($request->file('attachments', []) as $file) {
                $path = $file->store('complaint-evidence', 'local');
                ComplaintAttachment::create([
                    'complaint_id' => $complaint->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }

            return $complaint;
        });

        return redirect()->route('complaints.show', $complaint)->with('success', 'Complaint submitted. Your reference number is '.$complaint->reference_no.'.');
    }

    public function show(Request $request, Complaint $complaint)
    {
        $this->authorizeComplaint($request, $complaint);
        $complaint->load(['student', 'category', 'assignee', 'history.administrator', 'attachments']);
        return view('complaints.show', compact('complaint'));
    }

    public function downloadAttachment(Request $request, ComplaintAttachment $attachment)
    {
        $complaint = $attachment->complaint()->with('category')->firstOrFail();
        $this->authorizeComplaint($request, $complaint);

        abort_unless(Storage::disk('local')->exists($attachment->file_path), 404);
        return Storage::disk('local')->download($attachment->file_path, $attachment->original_name);
    }

    private function authorizeComplaint(Request $request, Complaint $complaint): void
    {
        $user = $request->user();
        if ($user->role === 'student' && $complaint->student_id !== $user->id) {
            abort(403);
        }
        if ($user->isAdmin() && $user->role !== 'super_admin' && $complaint->assigned_to !== $user->id) {
            abort(403);
        }
    }
}
