<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->string('response_type')->default('both');
            $table->smallInteger('max_points')->default(10);
            $table->smallInteger('passing_score')->nullable();
            $table->integer('max_attempts')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->boolean('is_required')->default(false);
            $table->json('allowed_extensions')->nullable();
            $table->smallInteger('max_file_size_mb')->default(10);
            $table->smallInteger('max_files')->default(5);
            $table->timestamps();

            $table->index('is_required');
        });

        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->constrained('homeworks')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->integer('attempt_number')->default(1);
            $table->text('text_response')->nullable();
            $table->json('files')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_late')->default(false);
            $table->smallInteger('score')->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(['homework_id', 'student_id', 'attempt_number']);
            $table->index('status');
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homework_submissions');
        Schema::dropIfExists('homeworks');
    }
};
