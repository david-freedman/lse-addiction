<?php

namespace Database\Factories;

use App\Domains\Homework\Enums\HomeworkResponseType;
use App\Domains\Homework\Models\Homework;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkFactory extends Factory
{
    protected $model = Homework::class;

    public function definition(): array
    {
        return [
            'lesson_id' => null,
            'description' => fake()->paragraph(),
            'response_type' => fake()->randomElement(HomeworkResponseType::cases()),
            'max_points' => fake()->randomElement([10, 20, 50, 100]),
            'passing_score' => fake()->optional()->randomElement([50, 60, 70, 80]),
            'max_attempts' => fake()->optional()->randomElement([1, 2, 3, 5]),
            'deadline_at' => fake()->optional()->dateTimeBetween('now', '+2 months'),
            'is_required' => fake()->boolean(30),
            'allowed_extensions' => fake()->optional()->randomElements(['pdf', 'doc', 'docx', 'txt', 'zip'], rand(1, 5)),
            'max_file_size_mb' => fake()->randomElement([5, 10, 20]),
            'max_files' => fake()->randomElement([1, 3, 5, 10]),
        ];
    }

    public function required(): static
    {
        return $this->state(fn () => ['is_required' => true]);
    }

    public function textOnly(): static
    {
        return $this->state(fn () => ['response_type' => HomeworkResponseType::Text]);
    }

    public function filesOnly(): static
    {
        return $this->state(fn () => ['response_type' => HomeworkResponseType::Files]);
    }

    public function withDeadline(): static
    {
        return $this->state(fn () => [
            'deadline_at' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }
}
