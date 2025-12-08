<?php

namespace Database\Factories;

use App\Domains\Quiz\Enums\QuestionType;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizQuestion>
 */
class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'type' => fake()->randomElement(QuestionType::cases()),
            'question_text' => fake()->sentence(8) . '?',
            'question_image' => null,
            'order' => 1,
            'points' => fake()->randomElement([1, 2, 3, 5]),
        ];
    }

    public function singleChoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => QuestionType::SingleChoice,
        ]);
    }

    public function multipleChoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => QuestionType::MultipleChoice,
        ]);
    }

    public function imageSelect(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => QuestionType::ImageSelect,
        ]);
    }

    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'question_image' => 'https://placehold.co/400x300',
        ]);
    }
}
