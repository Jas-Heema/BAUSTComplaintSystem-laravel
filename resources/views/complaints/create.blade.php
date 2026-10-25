@extends('layouts.app')
@section('title', 'Submit a complaint')
@section('content')
<div style="max-width:800px;margin:auto">
    <div class="section-head"><div><h1>Submit a complaint</h1><p class="muted">Please provide accurate details so the appropriate authority can review your concern.</p></div><a href="{{ route('complaints.index') }}">My complaints</a></div>
    <div class="card"><form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">@csrf
        <div class="field"><label for="title">Complaint title *</label><input id="title" name="title" value="{{ old('title') }}" required maxlength="180" placeholder="Briefly describe your concern"></div>
        <div class="field"><label for="category_id">Category *</label><select id="category_id" name="category_id" required><option value="">Select a category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select><p class="small">Your category determines which university authority receives the complaint.</p></div>
        <div class="field"><label for="description">Detailed description *</label><textarea id="description" name="description" required minlength="10" maxlength="10000" placeholder="Explain what happened, where it happened, and any relevant details.">{{ old('description') }}</textarea></div>
        <div class="field"><label for="incident_date">Incident date (optional)</label><input id="incident_date" type="date" name="incident_date" value="{{ old('incident_date') }}" max="{{ date('Y-m-d') }}"></div>
        <div class="field"><label for="attachments">Supporting evidence (optional)</label><input id="attachments" type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"><p class="small">Allowed: JPG, PNG, PDF. Maximum 5 MB per file.</p></div>
        <button class="btn" type="submit">Submit complaint</button> <a class="btn secondary" href="{{ route('dashboard') }}">Cancel</a>
    </form></div>
</div>
@endsection
