<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['name' => 'CSE Department Head', 'email' => 'cse.admin@baust.local', 'role' => 'cse_admin'],
            ['name' => 'Hall Provost', 'email' => 'hall.admin@baust.local', 'role' => 'hall_admin'],
            ['name' => 'Chief Librarian', 'email' => 'library.admin@baust.local', 'role' => 'library_admin'],
            ['name' => 'University Proctor', 'email' => 'proctor.admin@baust.local', 'role' => 'proctor'],
            ['name' => 'System Administrator', 'email' => 'super.admin@baust.local', 'role' => 'super_admin'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => 'Password123!',
                    'role' => $admin['role'],
                    'is_active' => true,
                ]
            );
        }

        $categories = [
            ['name' => 'Academic', 'description' => 'Academic and course-related matters', 'assigned_role' => 'cse_admin'],
            ['name' => 'Laboratory', 'description' => 'Laboratory equipment and facilities', 'assigned_role' => 'cse_admin'],
            ['name' => 'Classroom', 'description' => 'Classroom and teaching facilities', 'assigned_role' => 'cse_admin'],
            ['name' => 'Varsity / CSE Department', 'description' => 'Department-specific university matters', 'assigned_role' => 'cse_admin'],
            ['name' => 'Administrative', 'description' => 'General university administration', 'assigned_role' => 'proctor'],
            ['name' => 'Transportation', 'description' => 'University transportation services', 'assigned_role' => 'proctor'],
            ['name' => 'Hostel / Hall', 'description' => 'Residential and hall facilities', 'assigned_role' => 'hall_admin'],
            ['name' => 'Campus Facilities', 'description' => 'General campus facilities', 'assigned_role' => 'proctor'],
            ['name' => 'Library', 'description' => 'Library services and facilities', 'assigned_role' => 'library_admin'],
            ['name' => 'Other', 'description' => 'Other campus-related complaints', 'assigned_role' => 'proctor'],
        ];

        foreach ($categories as $category) {
            ComplaintCategory::updateOrCreate(['name' => $category['name']], $category + ['is_active' => true]);
        }
    }
}
