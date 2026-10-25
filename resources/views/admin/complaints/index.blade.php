@extends('layouts.app')
@section('title', 'Assigned complaints')
@section('content')
<div class="section-head"><div><p class="small">ADMINISTRATION</p><h1>Assigned complaints</h1><p class="muted">Only complaints assigned to your authority are shown.</p></div></div>
<div class="card">
    <form class="filter" method="GET"><input type="search" name="q" value="{{ request('q') }}" placeholder="Reference, title, student ID or name">
        <select name="status"><option value="">All statuses</option>@foreach(['Pending','In Progress','Resolved'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
        <select name="category_id"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select>
        <button class="btn smallbtn" type="submit">Filter</button><a class="small" href="{{ route('admin.complaints.index') }}">Clear</a>
    </form>
    @if($complaints->isEmpty())<div class="empty">No complaints found for these filters.</div>
    @else<div class="table-wrap"><table><thead><tr><th>Reference</th><th>Student</th><th>Complaint</th><th>Category</th><th>Status</th><th>Submitted</th><th></th></tr></thead><tbody>
    @foreach($complaints as $complaint)<tr><td><strong>{{ $complaint->reference_no }}</strong></td><td>{{ $complaint->student->name }}<br><span class="small">{{ $complaint->student->student_id }}</span></td><td>{{ $complaint->title }}</td><td>{{ $complaint->category->name }}</td><td><span class="badge {{ $complaint->status_class }}">{{ $complaint->status }}</span></td><td>{{ $complaint->created_at->format('d M Y') }}</td><td><a href="{{ route('admin.complaints.show', $complaint) }}">Review</a></td></tr>@endforeach
    </tbody></table></div><div class="pagination">{{ $complaints->links() }}</div>@endif
</div>
@endsection
