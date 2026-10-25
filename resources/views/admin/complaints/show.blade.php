@extends('layouts.app')
@section('title', 'Review complaint')
@section('content')
<div class="section-head"><div><p class="small">COMPLAINT REVIEW</p><h1 style="font-size:25px">{{ $complaint->title }}</h1><p class="muted">Reference: <strong>{{ $complaint->reference_no }}</strong></p></div><span class="badge {{ $complaint->status_class }}">{{ $complaint->status }}</span></div>
<div class="grid two">
    <div class="card"><h2>Complaint details</h2><p><span class="muted">Student</span><br><strong>{{ $complaint->student->name }}</strong> ({{ $complaint->student->student_id }})</p><p><span class="muted">Department</span><br>{{ $complaint->student->department ?? 'Not provided' }}</p><p><span class="muted">Email</span><br>{{ $complaint->student->email }}</p><p><span class="muted">Category</span><br>{{ $complaint->category->name }}</p><p><span class="muted">Submitted</span><br>{{ $complaint->created_at->format('d M Y, h:i A') }}</p><h3>Description</h3><p style="white-space:pre-line">{{ $complaint->description }}</p>
        @if($complaint->attachments->isNotEmpty())<h3>Evidence</h3>@foreach($complaint->attachments as $attachment)<p><a href="{{ route('attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></p>@endforeach @endif
    </div>
    <div>
        <div class="card"><h2>Update status</h2><form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">@csrf @method('PATCH')
            <div class="field"><label for="status">New status</label><select name="status" id="status" required>@foreach(['Pending','In Progress','Resolved'] as $status)<option value="{{ $status }}" @selected(old('status', $complaint->status) === $status)>{{ $status }}</option>@endforeach</select></div>
            <div class="field"><label for="remark">Administrative remark *</label><textarea id="remark" name="remark" required minlength="3" maxlength="3000" placeholder="Describe the action taken or next steps.">{{ old('remark') }}</textarea></div>
            <button class="btn" type="submit">Save update</button>
        </form></div>
        <div class="card"><h2>History</h2><div class="timeline">@forelse($complaint->history as $event)<div class="event"><strong>{{ $event->new_status }}</strong><div class="small">{{ $event->created_at->format('d M Y, h:i A') }}@if($event->administrator) · {{ $event->administrator->name }}@endif</div><p>{{ $event->remark ?: 'No remark.' }}</p></div>@empty<p class="muted">No history.</p>@endforelse</div></div>
    </div>
</div>
<a class="btn secondary" href="{{ route('admin.complaints.index') }}">← Back to assigned complaints</a>
@endsection
