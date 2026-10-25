<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('complaint_categories')->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->date('incident_date')->nullable();
            $table->string('status')->default('Pending')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
            $table->index(['assigned_to', 'status']);
            $table->index(['student_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
