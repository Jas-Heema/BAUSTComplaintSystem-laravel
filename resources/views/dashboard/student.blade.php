@extends('layouts.app')
@section('title', 'Student dashboard')
@section('content')
<div class="section-head"><div><p class="small">STUDENT PORTAL</p><h1 style="margin-bottom:5px">Hello, {{ auth()->user()->name }}</h1><p class="muted">Here is the latest overview of your complaints.</p></div><a class="btn" href="{{ route('complaints.create') }}">＋ Submit complaint</a></div>
<div class="grid">
    <div class="stat"><span>Total complaints</span><strong>{{ $counts['total'] }}</strong></div>
    <div class="stat"><span>Pending</span><strong>{{ $counts['pending'] }}</strong></div>
    <div class="stat"><span>In progress</span><strong>{{ $counts['progress'] }}</strong></div>
    <div class="stat"><span>Resolved</span><strong>{{ $counts['resolved'] }}</strong></div>
</div>
<div class="card" style="margin-top:22px">
    <div class="section-head"><h2 style="margin:0">Recent complaints</h2><a href="{{ route('complaints.index') }}">View all →</a></div>
    @if($complaints->isEmpty())<div class="empty">You have not submitted any complaints yet.<br><a href="{{ route('complaints.create') }}">Submit your first complaint</a></div>
    @else<div class="table-wrap"><table><thead><tr><th>Reference</th><th>Title</th><th>Category</th><th>Status</th><th>Submitted</th><th></th></tr></thead><tbody>
    @foreach($complaints as $complaint)<tr><td><strong>{{ $complaint->reference_no }}</strong></td><td>{{ $complaint->title }}</td><td>{{ $complaint->category->name }}</td><td><span class="badge {{ $complaint->status_class }}">{{ $complaint->status }}</span></td><td>{{ $complaint->created_at->format('d M Y') }}</td><td><a href="{{ route('complaints.show', $complaint) }}">Details</a></td></tr>@endforeach
    </tbody></table></div>@endif
</div>
<div class="card"><h2>Recent updates</h2>
    @forelse($notifications as $notification)<p style="border-bottom:1px solid #edf0f5;padding:8px 0;margin:0">{{ $notification->message }} <span class="small">· {{ $notification->created_at->diffForHumans() }}</span></p>
    @empty<p class="muted">No updates yet. New status updates will appear here.</p>@endforelse
</div>
@endsection
