<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('banner')->nullable();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->timestamp('starts_at');
            $table->unsignedSmallInteger('duration_minutes')->default(90);
            $table->string('meeting_url')->nullable();
            $table->string('recording_url')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('max_participants')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('starts_at');
            $table->index('status');
            $table->index('teacher_id');
            $table->index(['starts_at', 'status']);
        });

        Schema::create('webinar_student', function (Blueprint $table) {
            $table->foreignId('webinar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('attended_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();

            $table->unique(['webinar_id', 'student_id']);
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webinar_student');
        Schema::dropIfExists('webinars');
    }
};
