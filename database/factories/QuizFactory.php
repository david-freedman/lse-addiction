<?php

namespace Database\Factories;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'quizzable_type' => Lesson::class,
            'quizzable_id' => Lesson::factory(),
            'title' => fake()->optional()->sentence(3),
            'passing_score' => fake()->randomElement([60, 70, 80, 90]),
            'max_attempts' => fake()->optional()->randomElement([1, 2, 3, 5, null]),
            'time_limit_minutes' => fake()->optional()->randomElement([15, 30, 45, 60, null]),
            'show_correct_answers' => fake()->boolean(80),
        ];
    }

    public function forLesson(Lesson $lesson): static
    {
        return $this->state(fn (array $attributes) => [
            'quizzable_type' => Lesson::class,
            'quizzable_id' => $lesson->id,
        ]);
    }

    public function forModule($module): static
    {
        return $this->state(fn (array $attributes) => [
            'quizzable_type' => \App\Domains\Module\Models\Module::class,
            'quizzable_id' => $module->id,
        ]);
    }

    public function unlimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_attempts' => null,
            'time_limit_minutes' => null,
        ]);
    }

    public function strict(): static
    {
        return $this->state(fn (array $attributes) => [
            'passing_score' => 90,
            'max_attempts' => 1,
            'show_correct_answers' => false,
        ]);
    }
}
