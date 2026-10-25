@extends('layouts.app')
@section('title', $complaint->reference_no)
@section('content')
<div class="section-head"><div><p class="small">COMPLAINT DETAILS</p><h1 style="font-size:25px;margin-bottom:7px">{{ $complaint->title }}</h1><p class="muted">Reference: <strong>{{ $complaint->reference_no }}</strong></p></div><span class="badge {{ $complaint->status_class }}">{{ $complaint->status }}</span></div>
<div class="grid two">
    <div class="card"><h2>Complaint information</h2><p><span class="muted">Category</span><br><strong>{{ $complaint->category->name }}</strong></p><p><span class="muted">Submitted</span><br>{{ $complaint->created_at->format('d M Y, h:i A') }}</p><p><span class="muted">Incident date</span><br>{{ $complaint->incident_date?->format('d M Y') ?? 'Not provided' }}</p><p><span class="muted">Assigned authority</span><br>{{ $complaint->assignee?->name ?? 'Not assigned' }}</p><hr style="border:0;border-top:1px solid #e8edf4"><h3>Description</h3><p style="white-space:pre-line">{{ $complaint->description }}</p>
    @if($complaint->attachments->isNotEmpty())<h3>Evidence files</h3>@foreach($complaint->attachments as $attachment)<p><a href="{{ route('attachments.download', $attachment) }}">{{ $attachment->original_name }}</a> <span class="small">({{ number_format($attachment->file_size / 1024, 1) }} KB)</span></p>@endforeach @endif
    </div>
    <div class="card"><h2>Status history</h2><div class="timeline">
        @forelse($complaint->history as $event)<div class="event"><strong>{{ $event->new_status }}</strong><div class="small">{{ $event->created_at->format('d M Y, h:i A') }}@if($event->administrator) · {{ $event->administrator->name }}@endif</div><p style="margin:6px 0 0">{{ $event->remark ?: 'No remark provided.' }}</p></div>@empty<p class="muted">No history recorded.</p>@endforelse
    </div></div>
</div>
<a class="btn secondary" href="{{ route('complaints.index') }}">← Back to my complaints</a>
@endsection
