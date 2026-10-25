<?php

namespace App\Http\Controllers;

use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SuperAdminController extends Controller
{
    public function categories()
    {
        $categories = ComplaintCategory::orderBy('name')->get();
        return view('superadmin.categories', compact('categories'));
    }

    public function saveCategory(Request $request, ?ComplaintCategory $category = null)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('complaint_categories', 'name')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'assigned_role' => ['required', Rule::in(['cse_admin', 'hall_admin', 'library_admin', 'proctor'])],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        if ($category && $category->exists) {
            $category->update($data);
        } else {
            ComplaintCategory::create($data);
        }

        return redirect()->route('superadmin.categories')->with('success', 'Category saved.');
    }

    public function administrators()
    {
        $administrators = User::whereIn('role', ['cse_admin', 'hall_admin', 'library_admin', 'proctor', 'super_admin'])->orderBy('role')->get();
        return view('superadmin.administrators', compact('administrators'));
    }

    public function storeAdministrator(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in(['cse_admin', 'hall_admin', 'library_admin', 'proctor', 'super_admin'])],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        User::create($data + ['is_active' => true]);
        return redirect()->route('superadmin.administrators')->with('success', 'Administrator account created.');
    }

    public function toggleAdministrator(User $user)
    {
        abort_if($user->id === auth()->id(), 422, 'You cannot deactivate your own account.');
        abort_if($user->role === 'student', 422);
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Administrator account status changed.');
    }
}
