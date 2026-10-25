@extends('layouts.app')
@section('content')
<div class="panel-head">
  <div>
    <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h1>
    <p class="sub">File a new complaint or check where your open ones stand.</p>
  </div>
  <a href="#submit" class="btn btn-primary">+ New complaint</a>
</div>

<div class="kpi-row">
  <div class="kpi accent"><div class="n">{{ $counts['total'] }}</div><div class="l">Total filed</div></div>
  <div class="kpi"><div class="n">{{ $counts['pending'] }}</div><div class="l">Pending</div></div>
  <div class="kpi"><div class="n">{{ $counts['progress'] }}</div><div class="l">In progress</div></div>
  <div class="kpi"><div class="n">{{ $counts['resolved'] }}</div><div class="l">Resolved</div></div>
</div>

<div class="panel-card" id="submit">
  <div class="panel-card-head"><h2>Submit a new complaint</h2></div>
  <div style="padding:24px;">
    <form method="POST" action="{{ route('student.complaints.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="field">
        <label>Category</label>
        <div class="category-pick">
          @foreach($categories as $cat)
            <label class="cat-option">
              <input type="radio" name="category_id" value="{{ $cat->id }}" style="display:none"
                     @checked(old('category_id') == $cat->id)>
              {{ $cat->name }}
              <span class="goes-to">&rarr; {{ $cat->authority_label }}</span>
            </label>
          @endforeach
        </div>
        @error('category_id')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="field">
        <label for="title">Complaint title</label>
        <input id="title" name="title" value="{{ old('title') }}" required>
        @error('title')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
        @error('description')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="field-row">
        <div class="field">
          <label for="incident_date">Date of incident</label>
          <input id="incident_date" name="incident_date" type="date" value="{{ old('incident_date') }}" required>
          @error('incident_date')<div class="err">{{ $message }}</div>@enderror
        </div>
        <div class="field">
          <label for="attachment">Attachment (optional)</label>
          <input id="attachment" name="attachment" type="file">
          @error('attachment')<div class="err">{{ $message }}</div>@enderror
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Submit complaint</button>
    </form>
  </div>
</div>

<div class="panel-card" id="mine">
  <div class="panel-card-head"><h2>My complaints</h2></div>
  <table class="data">
    <thead><tr><th>Reference</th><th>Category</th><th>Routed to</th><th>Filed</th><th>Status</th></tr></thead>
    <tbody>
      @forelse($complaints as $c)
        <tr>
          <td class="ref-cell">{{ $c->reference }}</td>
          <td><span class="cat-chip">{{ $c->category->name }}</span></td>
          <td>{{ $c->category->authority_label }}</td>
          <td>{{ $c->created_at->format('j M Y') }}</td>
          <td><span class="status-pill {{ $c->status }}"><span class="dot"></span> {{ $c->status_label }}</span></td>
        </tr>
      @empty
        <tr><td colspan="5">No complaints yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
