@extends('layouts.app')
@section('content')
<div class="panel-head">
  <div>
    <h1>My queue</h1>
    <p class="sub">Complaints routed to you. No other authority sees these.</p>
  </div>
</div>

<div class="kpi-row">
  <div class="kpi accent"><div class="n">{{ $kpi['total'] }}</div><div class="l">In my queue</div></div>
  <div class="kpi"><div class="n">{{ $kpi['pending'] }}</div><div class="l">Pending</div></div>
  <div class="kpi"><div class="n">{{ $kpi['progress'] }}</div><div class="l">In progress</div></div>
  <div class="kpi"><div class="n">{{ $kpi['resolved'] }}</div><div class="l">Resolved</div></div>
</div>

<div class="panel-card">
  <div class="panel-card-head"><h2>Complaints</h2></div>
  <table class="data">
    <thead><tr><th>Reference</th><th>Student</th><th>Title</th><th>Filed</th><th>Status</th><th>Update</th></tr></thead>
    <tbody>
      @forelse($complaints as $c)
        <tr>
          <td class="ref-cell">{{ $c->reference }}</td>
          <td>{{ $c->user->name }}</td>
          <td>{{ $c->title }}</td>
          <td>{{ $c->created_at->format('j M Y') }}</td>
          <td><span class="status-pill {{ $c->status }}"><span class="dot"></span> {{ $c->status_label }}</span></td>
          <td>
            <form method="POST" action="{{ route('desk.complaints.update', $c) }}" class="mini-form">
              @csrf
              @method('PATCH')
              <select name="status">
                <option value="pending" @selected($c->status === 'pending')>Pending</option>
                <option value="progress" @selected($c->status === 'progress')>In progress</option>
                <option value="resolved" @selected($c->status === 'resolved')>Resolved</option>
              </select>
              <input name="remark" value="{{ $c->remark }}" placeholder="Remark">
              <button type="submit">Save</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6">Nothing in your queue.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
