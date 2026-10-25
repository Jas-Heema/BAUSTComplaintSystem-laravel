@extends('layouts.app')
@section('title', 'Student registration')
@section('content')
<div style="max-width:650px;margin:25px auto">
    <div class="card">
        <h1 style="font-size:26px">Create student account</h1><p class="muted">Use your university information to register.</p>
        <form method="POST" action="{{ route('register.store') }}">@csrf
            <div class="form-grid">
                <div class="field"><label for="student_id">Student ID</label><input id="student_id" name="student_id" value="{{ old('student_id') }}" required maxlength="50"></div>
                <div class="field"><label for="name">Full name</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="120"></div>
                <div class="field"><label for="department">Department</label><input id="department" name="department" value="{{ old('department', 'Computer Science and Engineering (CSE)') }}" required maxlength="120"></div>
                <div class="field"><label for="email">University email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required></div>
                <div class="field"><label for="password">Password</label><input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"></div>
                <div class="field"><label for="password_confirmation">Confirm password</label><input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></div>
            </div>
            <button class="btn" type="submit">Create account</button>
        </form>
    </div>
</div>
@endsection
