<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_teacher', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->primary(['course_id', 'teacher_id']);
        });

        Schema::create('webinar_teacher', function (Blueprint $table) {
            $table->foreignId('webinar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->primary(['webinar_id', 'teacher_id']);
        });

        // Migrate existing teacher_id data into pivot tables
        \DB::statement('INSERT INTO course_teacher (course_id, teacher_id) SELECT id, teacher_id FROM courses WHERE teacher_id IS NOT NULL');
        \DB::statement('INSERT INTO webinar_teacher (webinar_id, teacher_id) SELECT id, teacher_id FROM webinars WHERE teacher_id IS NOT NULL');

        // Make teacher_id nullable (kept for backward compatibility)
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->change();
        });

        Schema::table('webinars', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webinar_teacher');
        Schema::dropIfExists('course_teacher');

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable(false)->change();
        });

        Schema::table('webinars', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable(false)->change();
        });
    }
};
