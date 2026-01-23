<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('number', 7)->unique();
            $table->text('description');
            $table->text('description_short')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('discount_percentage')->nullable();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('banner')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('draft');
            $table->string('type')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->string('label')->nullable();
            $table->boolean('is_sequential')->default(true);
            $table->boolean('requires_certificate_approval')->default(true);
            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index('starts_at');
            $table->index('teacher_id');
            $table->index('author_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
