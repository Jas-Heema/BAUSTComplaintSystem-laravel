@extends('layouts.app')
@section('title', 'Administrator dashboard')
@section('content')
<div class="section-head"><div><p class="small">ADMINISTRATIVE PORTAL · {{ strtoupper(str_replace('_',' ',auth()->user()->role)) }}</p><h1 style="margin-bottom:5px">Complaint overview</h1><p class="muted">Review and manage complaints assigned to your authority.</p></div><a class="btn" href="{{ route('admin.complaints.index') }}">View assigned complaints</a></div>
<div class="grid">
    <div class="stat"><span>Total assigned</span><strong>{{ $counts['total'] }}</strong></div>
    <div class="stat"><span>Pending</span><strong>{{ $counts['pending'] }}</strong></div>
    <div class="stat"><span>In progress</span><strong>{{ $counts['progress'] }}</strong></div>
    <div class="stat"><span>Resolved</span><strong>{{ $counts['resolved'] }}</strong></div>
</div>
<div class="card" style="margin-top:22px"><div class="section-head"><h2 style="margin:0">Recently submitted</h2><a href="{{ route('admin.complaints.index') }}">View all →</a></div>
@if($complaints->isEmpty())<div class="empty">No complaints are currently assigned to this authority.</div>
@else<div class="table-wrap"><table><thead><tr><th>Reference</th><th>Student</th><th>Title</th><th>Category</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($complaints as $complaint)<tr><td>{{ $complaint->reference_no }}</td><td>{{ $complaint->student->name }}<br><span class="small">{{ $complaint->student->student_id }}</span></td><td>{{ $complaint->title }}</td><td>{{ $complaint->category->name }}</td><td><span class="badge {{ $complaint->status_class }}">{{ $complaint->status }}</span></td><td><a href="{{ route('admin.complaints.show', $complaint) }}">Review</a></td></tr>@endforeach
</tbody></table></div>@endif</div>
@endsection
