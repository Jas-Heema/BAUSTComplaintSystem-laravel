@extends('layouts.app')
@section('title', 'My complaints')
@section('content')
<div class="section-head"><div><h1>My complaints</h1><p class="muted">Search your records and track each complaint's status.</p></div><a class="btn" href="{{ route('complaints.create') }}">＋ New complaint</a></div>
<div class="card">
    <form class="filter" method="GET"><input type="search" name="q" value="{{ request('q') }}" placeholder="Search reference or title">
        <select name="status"><option value="">All statuses</option>@foreach(['Pending','In Progress','Resolved'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
        <button class="btn smallbtn" type="submit">Filter</button><a href="{{ route('complaints.index') }}" class="small">Clear</a>
    </form>
    @if($complaints->isEmpty())<div class="empty">No matching complaints found.</div>
    @else<div class="table-wrap"><table><thead><tr><th>Reference</th><th>Title</th><th>Category</th><th>Assigned authority</th><th>Status</th><th>Date</th><th></th></tr></thead><tbody>
    @foreach($complaints as $complaint)<tr><td><strong>{{ $complaint->reference_no }}</strong></td><td>{{ $complaint->title }}</td><td>{{ $complaint->category->name }}</td><td>{{ $complaint->assignee?->name ?? 'Not assigned' }}</td><td><span class="badge {{ $complaint->status_class }}">{{ $complaint->status }}</span></td><td>{{ $complaint->created_at->format('d M Y') }}</td><td><a href="{{ route('complaints.show', $complaint) }}">Details</a></td></tr>@endforeach
    </tbody></table></div><div class="pagination">{{ $complaints->links() }}</div>@endif
</div>
@endsection
