<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->morphs('quizzable');
            $table->string('title')->nullable();
            $table->unsignedTinyInteger('passing_score')->default(70);
            $table->unsignedInteger('max_attempts')->nullable();
            $table->unsignedInteger('time_limit_minutes')->nullable();
            $table->boolean('show_correct_answers')->default(true);
            $table->timestamps();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('points')->default(1);
            $table->timestamps();

            $table->index(['quiz_id', 'order']);
        });

        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->string('answer_image')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->string('category')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
