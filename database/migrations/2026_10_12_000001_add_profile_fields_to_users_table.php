<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id')->nullable()->unique()->after('id');
            $table->string('department')->nullable()->after('email');
            $table->string('role')->default('student')->index()->after('password');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['student_id']);
            $table->dropIndex(['role']);
            $table->dropColumn(['student_id', 'department', 'role', 'is_active']);
        });
    }
};
