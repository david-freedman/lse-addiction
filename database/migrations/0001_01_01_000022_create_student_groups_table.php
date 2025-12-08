<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('course_id');
        });

        Schema::create('student_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('student_groups')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamp('added_at')->useCurrent();

            $table->unique(['group_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_group_members');
        Schema::dropIfExists('student_groups');
    }
};
