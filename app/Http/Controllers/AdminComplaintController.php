<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\PortalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['student', 'category', 'assignee']);
        if ($request->user()->role !== 'super_admin') {
            $query->where('assigned_to', $request->user()->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $query->where(function ($q) use ($term) {
                $q->where('reference_no', 'like', $term)
                    ->orWhere('title', 'like', $term)
                    ->orWhereHas('student', fn ($s) => $s->where('student_id', 'like', $term)->orWhere('name', 'like', $term));
            });
        }

        $complaints = $query->latest()->paginate(12)->withQueryString();
        $categories = \App\Models\ComplaintCategory::orderBy('name')->get();
        return view('admin.complaints.index', compact('complaints', 'categories'));
    }

    public function show(Request $request, Complaint $complaint)
    {
        $this->authorizeAssigned($request, $complaint);
        $complaint->load(['student', 'category', 'assignee', 'history.administrator', 'attachments']);
        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $this->authorizeAssigned($request, $complaint);

        $data = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'In Progress', 'Resolved'])],
            'remark' => ['required', 'string', 'min:3', 'max:3000'],
        ]);

        DB::transaction(function () use ($request, $complaint, $data) {
            $previous = $complaint->status;
            $complaint->update(['status' => $data['status'], 'last_updated_at' => now()]);

            ComplaintStatusHistory::create([
                'complaint_id' => $complaint->id,
                'previous_status' => $previous,
                'new_status' => $data['status'],
                'administrator_id' => $request->user()->id,
                'remark' => $data['remark'],
            ]);

            PortalNotification::create([
                'recipient_id' => $complaint->student_id,
                'complaint_id' => $complaint->id,
                'message' => 'Complaint '.$complaint->reference_no.' status changed to '.$data['status'].'.',
            ]);
        });

        return redirect()->route('admin.complaints.show', $complaint)->with('success', 'Complaint status and history updated.');
    }

    private function authorizeAssigned(Request $request, Complaint $complaint): void
    {
        $user = $request->user();
        if ($user->role !== 'super_admin' && $complaint->assigned_to !== $user->id) {
            abort(403, 'This complaint is assigned to another authority.');
        }
    }
}
