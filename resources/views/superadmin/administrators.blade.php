@extends('layouts.app')
@section('title', 'Administrator accounts')
@section('content')
<div class="section-head"><div><p class="small">SYSTEM CONFIGURATION</p><h1>Administrator accounts</h1><p class="muted">Only the super administrator can create or deactivate administrator accounts.</p></div></div>
<div class="grid two">
    <div class="card"><h2>Create administrator</h2><form method="POST" action="{{ route('superadmin.administrators.store') }}">@csrf
        <div class="field"><label>Name</label><input name="name" required maxlength="120"></div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Role</label><select name="role" required>@foreach(['cse_admin'=>'CSE Department Head','hall_admin'=>'Hall Provost','library_admin'=>'Chief Librarian','proctor'=>'Proctor','super_admin'=>'Super Administrator'] as $role=>$label)<option value="{{ $role }}">{{ $label }}</option>@endforeach</select></div>
        <div class="field"><label>Password</label><input type="password" name="password" minlength="8" required></div>
        <div class="field"><label>Confirm password</label><input type="password" name="password_confirmation" minlength="8" required></div>
        <button class="btn" type="submit">Create account</button>
    </form></div>
    <div class="card"><h2>Access roles</h2><p class="muted">CSE Head handles department-related complaints; Hall Provost handles hall complaints; Chief Librarian handles library complaints; Proctor handles other campus complaints. Super Admin manages configuration.</p><p class="small">Use strong unique passwords and change all demo credentials before any real deployment.</p></div>
</div>
<div class="card"><h2>Existing administrators</h2><div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Account</th><th>Action</th></tr></thead><tbody>
@foreach($administrators as $admin)<tr><td>{{ $admin->name }}</td><td>{{ $admin->email }}</td><td>{{ $admin->roleLabel() }}</td><td>{{ $admin->is_active ? 'Active' : 'Inactive' }}</td><td>@if($admin->id !== auth()->id())<form method="POST" action="{{ route('superadmin.administrators.toggle', $admin) }}">@csrf @method('PATCH')<button class="btn smallbtn {{ $admin->is_active ? 'danger' : 'secondary' }}" type="submit">{{ $admin->is_active ? 'Deactivate' : 'Activate' }}</button></form>@else<span class="small">Current account</span>@endif</td></tr>@endforeach
</tbody></table></div></div>
@endsection
