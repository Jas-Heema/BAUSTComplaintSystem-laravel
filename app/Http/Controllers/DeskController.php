<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class DeskController extends Controller
{
    public function index(Request $r)
    {
        // Staff only ever see the queue routed to their own role
        $complaints = Complaint::with(['user', 'category'])
            ->where('routed_to', $r->user()->role)
            ->latest()
            ->get();

        $kpi = [
            'total'    => $complaints->count(),
            'pending'  => $complaints->where('status', 'pending')->count(),
            'progress' => $complaints->where('status', 'progress')->count(),
            'resolved' => $complaints->where('status', 'resolved')->count(),
        ];

        return view('desk.index', compact('complaints', 'kpi'));
    }

    public function update(Request $r, Complaint $complaint)
    {
        // Blocks staff from editing another desk's complaint by changing the URL
        abort_unless($complaint->routed_to === $r->user()->role, 403);

        $data = $r->validate([
            'status' => 'required|in:pending,progress,resolved',
            'remark' => 'nullable|string|max:1000',
        ]);

        $complaint->update($data + [
            'resolved_at' => $data['status'] === 'resolved' ? now() : null,
        ]);

        return back()->with('ok', 'Complaint updated.');
    }
}
