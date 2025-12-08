<?php

namespace Database\Factories;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentLessonProgress>
 */
class StudentLessonProgressFactory extends Factory
{
    protected $model = StudentLessonProgress::class;

    public function definition(): array
    {
        $status = fake()->randomElement(ProgressStatus::cases());
        $startedAt = fake()->dateTimeBetween('-3 months', '-1 week');

        return [
            'student_id' => Student::factory(),
            'lesson_id' => Lesson::factory(),
            'status' => $status,
            'started_at' => $status !== ProgressStatus::NotStarted ? $startedAt : null,
            'completed_at' => $status === ProgressStatus::Completed
                ? fake()->dateTimeBetween($startedAt, 'now')
                : null,
        ];
    }

    public function notStarted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProgressStatus::NotStarted,
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProgressStatus::InProgress,
            'started_at' => fake()->dateTimeBetween('-3 months', '-1 week'),
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        $startedAt = fake()->dateTimeBetween('-3 months', '-1 week');

        return $this->state(fn (array $attributes) => [
            'status' => ProgressStatus::Completed,
            'started_at' => $startedAt,
            'completed_at' => fake()->dateTimeBetween($startedAt, 'now'),
        ]);
    }
}
