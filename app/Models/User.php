<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'student_id', 'department',
        'role', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'student_id');
    }

    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            'cse_admin', 'hall_admin', 'library_admin', 'proctor', 'super_admin',
        ], true);
    }

    public function roleLabel(): string
    {
        return [
            'student' => 'Student',
            'cse_admin' => 'CSE Department Head',
            'hall_admin' => 'Hall Provost',
            'library_admin' => 'Chief Librarian',
            'proctor' => 'Proctor',
            'super_admin' => 'Super Administrator',
        ][$this->role] ?? 'User';
    }
}
