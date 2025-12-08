<?php

namespace Database\Factories;

use App\Domains\Quiz\Models\QuizAnswer;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizAnswer>
 */
class QuizAnswerFactory extends Factory
{
    protected $model = QuizAnswer::class;

    public function definition(): array
    {
        return [
            'question_id' => QuizQuestion::factory(),
            'answer_text' => fake()->sentence(4),
            'answer_image' => null,
            'is_correct' => false,
            'category' => null,
            'order' => 1,
        ];
    }

    public function correct(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_correct' => true,
        ]);
    }

    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'answer_image' => 'https://placehold.co/200x150',
        ]);
    }

    public function inCategory(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }
}
