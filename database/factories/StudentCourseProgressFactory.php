<?php

namespace Database\Factories;

use App\Domains\Course\Models\Course;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentCourseProgress>
 */
class StudentCourseProgressFactory extends Factory
{
    protected $model = StudentCourseProgress::class;

    public function definition(): array
    {
        $status = fake()->randomElement(ProgressStatus::cases());
        $startedAt = fake()->dateTimeBetween('-3 months', '-1 week');

        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'status' => $status,
            'progress_percentage' => $this->progressForStatus($status),
            'started_at' => $startedAt,
            'completed_at' => $status === ProgressStatus::Completed
                ? fake()->dateTimeBetween($startedAt, 'now')
                : null,
        ];
    }

    public function notStarted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProgressStatus::NotStarted,
            'progress_percentage' => 0,
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProgressStatus::InProgress,
            'progress_percentage' => fake()->numberBetween(10, 90),
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        $startedAt = fake()->dateTimeBetween('-3 months', '-1 week');

        return $this->state(fn (array $attributes) => [
            'status' => ProgressStatus::Completed,
            'progress_percentage' => 100,
            'started_at' => $startedAt,
            'completed_at' => fake()->dateTimeBetween($startedAt, 'now'),
        ]);
    }

    private function progressForStatus(ProgressStatus $status): float
    {
        return match ($status) {
            ProgressStatus::NotStarted => 0,
            ProgressStatus::InProgress => fake()->numberBetween(10, 90),
            ProgressStatus::Completed => 100,
        };
    }
}
