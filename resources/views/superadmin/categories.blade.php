@extends('layouts.app')
@section('title', 'Category settings')
@section('content')
<div class="section-head"><div><p class="small">SYSTEM CONFIGURATION</p><h1>Complaint categories</h1><p class="muted">Change the responsible authority for each category.</p></div></div>
<div class="grid two">
    <div class="card"><h2>Add a category</h2><form method="POST" action="{{ route('superadmin.categories.store') }}">@csrf
        <div class="field"><label>Name</label><input name="name" required maxlength="120"></div><div class="field"><label>Description</label><textarea name="description" style="min-height:75px"></textarea></div>
        <div class="field"><label>Responsible authority</label><select name="assigned_role" required>@foreach(['cse_admin'=>'CSE Department Head','hall_admin'=>'Hall Provost','library_admin'=>'Chief Librarian','proctor'=>'Proctor'] as $role=>$label)<option value="{{ $role }}">{{ $label }}</option>@endforeach</select></div>
        <label class="small"><input type="checkbox" name="is_active" value="1" checked> Active category</label><p><button class="btn" type="submit">Add category</button></p>
    </form></div>
    <div class="card"><h2>Routing guide</h2><p class="muted">CSE/academic → CSE Head · Hall/hostel → Hall Provost · Library → Chief Librarian · Other campus matters → Proctor.</p><p class="small">Changing a category affects new complaints; existing complaints keep their recorded assignment.</p></div>
</div>
<div class="card"><h2>Current categories</h2><div class="table-wrap"><table><thead><tr><th>Category</th><th>Assigned role</th><th>Status</th><th>Update routing</th></tr></thead><tbody>
@foreach($categories as $category)<tr><td><strong>{{ $category->name }}</strong><br><span class="small">{{ $category->description }}</span></td><td>{{ ['cse_admin'=>'CSE Department Head','hall_admin'=>'Hall Provost','library_admin'=>'Chief Librarian','proctor'=>'Proctor'][$category->assigned_role] ?? $category->assigned_role }}</td><td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td><td><form method="POST" action="{{ route('superadmin.categories.update', $category) }}">@csrf @method('PUT')
    <input type="hidden" name="name" value="{{ $category->name }}"><input type="hidden" name="description" value="{{ $category->description }}">
    <select name="assigned_role" aria-label="Assigned role">@foreach(['cse_admin'=>'CSE Head','hall_admin'=>'Hall Provost','library_admin'=>'Chief Librarian','proctor'=>'Proctor'] as $role=>$label)<option value="{{ $role }}" @selected($category->assigned_role === $role)>{{ $label }}</option>@endforeach</select>
    <input type="hidden" name="is_active" value="{{ $category->is_active ? 1 : 0 }}"><button class="btn smallbtn" type="submit">Save routing</button></form></td></tr>@endforeach
</tbody></table></div></div>
@endsection
