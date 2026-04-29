<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('webinar_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
            $table->unsignedInteger('course_id')->nullable()->change();
            $table->unique(['student_id', 'webinar_id']);
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'webinar_id']);
            $table->dropConstrainedForeignId('webinar_id');
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
        });
    }
};
