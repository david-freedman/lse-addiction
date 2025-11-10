<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->string('banner')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('draft');
            $table->string('type')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index('starts_at');
            $table->index('coach_id');
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
