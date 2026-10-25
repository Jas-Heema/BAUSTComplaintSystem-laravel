<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\PortalNotification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $query = Complaint::query();
            if ($user->role !== 'super_admin') {
                $query->where('assigned_to', $user->id);
            }

            $counts = [
                'total' => (clone $query)->count(),
                'pending' => (clone $query)->where('status', 'Pending')->count(),
                'progress' => (clone $query)->where('status', 'In Progress')->count(),
                'resolved' => (clone $query)->where('status', 'Resolved')->count(),
            ];

            $complaints = $query->with(['student', 'category', 'assignee'])->latest()->take(8)->get();
            return view('dashboard.admin', compact('counts', 'complaints'));
        }

        $query = Complaint::where('student_id', $user->id);
        $counts = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'Pending')->count(),
            'progress' => (clone $query)->where('status', 'In Progress')->count(),
            'resolved' => (clone $query)->where('status', 'Resolved')->count(),
        ];
        $complaints = $query->with(['category', 'assignee'])->latest()->take(8)->get();
        $notifications = PortalNotification::where('recipient_id', $user->id)->latest()->take(5)->get();

        return view('dashboard.student', compact('counts', 'complaints', 'notifications'));
    }
}
