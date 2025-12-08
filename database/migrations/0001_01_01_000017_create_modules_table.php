<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->string('status')->default('active');
            $table->boolean('has_final_test')->default(false);
            $table->string('unlock_rule')->default('none');
            $table->timestamps();

            $table->index(['course_id', 'order']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
