<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('type')->default('text');
            $table->string('video_url')->nullable();
            $table->string('dicom_source_type')->nullable();
            $table->string('dicom_file_path')->nullable();
            $table->string('dicom_url')->nullable();
            $table->json('dicom_metadata')->nullable();
            $table->string('qa_session_url')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->string('status')->default('draft');
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index(['module_id', 'order']);
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
