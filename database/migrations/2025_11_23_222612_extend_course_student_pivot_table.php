<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->after('course_id')->constrained('users')->nullOnDelete();
            $table->decimal('individual_discount', 5, 2)->default(0)->after('teacher_id')->comment('Персональна знижка в %');
            $table->unsignedInteger('lessons_completed')->default(0)->after('individual_discount')->comment('Мок дані - ручний ввід');
            $table->unsignedInteger('total_lessons')->default(0)->after('lessons_completed')->comment('Мок дані - ручний ввід');
            $table->timestamp('last_activity_at')->nullable()->after('total_lessons');
            $table->text('notes')->nullable()->after('last_activity_at')->comment('Нотатки адміна');

            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropIndex(['teacher_id']);
            $table->dropColumn([
                'teacher_id',
                'individual_discount',
                'lessons_completed',
                'total_lessons',
                'last_activity_at',
                'notes',
            ]);
        });
    }
};
