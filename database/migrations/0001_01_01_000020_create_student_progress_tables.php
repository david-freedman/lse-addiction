<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'lesson_id']);
            $table->index('status');
        });

        Schema::create('student_module_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('not_started');
            $table->unsignedInteger('lessons_completed')->default(0);
            $table->unsignedInteger('total_lessons')->default(0);
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'module_id']);
        });

        Schema::create('student_course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('not_started');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'course_id']);
        });

        Schema::create('student_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('attempt_number')->default(1);
            $table->decimal('score', 5, 2)->default(0);
            $table->decimal('max_score', 5, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->json('answers')->nullable();
            $table->unsignedInteger('time_spent_seconds')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'quiz_id', 'attempt_number']);
            $table->index('passed');
        });

        Schema::create('student_quiz_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->json('answers')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_quiz_drafts');
        Schema::dropIfExists('student_quiz_attempts');
        Schema::dropIfExists('student_course_progress');
        Schema::dropIfExists('student_module_progress');
        Schema::dropIfExists('student_lesson_progress');
    }
};
