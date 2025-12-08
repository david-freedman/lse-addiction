<?php

namespace Database\Factories;

use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentModuleProgress;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentModuleProgress>
 */
class StudentModuleProgressFactory extends Factory
{
    protected $model = StudentModuleProgress::class;

    public function definition(): array
    {
        $status = fake()->randomElement(ProgressStatus::cases());
        $totalLessons = fake()->numberBetween(3, 10);
        $startedAt = fake()->dateTimeBetween('-3 months', '-1 week');

        return [
            'student_id' => Student::factory(),
            'module_id' => Module::factory(),
            'status' => $status,
            'lessons_completed' => $this->lessonsCompletedForStatus($status, $totalLessons),
            'total_lessons' => $totalLessons,
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
            'lessons_completed' => 0,
            'progress_percentage' => 0,
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(function (array $attributes) {
            $total = $attributes['total_lessons'] ?? 5;
            $completed = fake()->numberBetween(1, $total - 1);

            return [
                'status' => ProgressStatus::InProgress,
                'lessons_completed' => $completed,
                'progress_percentage' => round(($completed / $total) * 100, 2),
                'completed_at' => null,
            ];
        });
    }

    public function completed(): static
    {
        $startedAt = fake()->dateTimeBetween('-3 months', '-1 week');

        return $this->state(function (array $attributes) use ($startedAt) {
            $total = $attributes['total_lessons'] ?? 5;

            return [
                'status' => ProgressStatus::Completed,
                'lessons_completed' => $total,
                'progress_percentage' => 100,
                'started_at' => $startedAt,
                'completed_at' => fake()->dateTimeBetween($startedAt, 'now'),
            ];
        });
    }

    private function lessonsCompletedForStatus(ProgressStatus $status, int $total): int
    {
        return match ($status) {
            ProgressStatus::NotStarted => 0,
            ProgressStatus::InProgress => fake()->numberBetween(1, $total - 1),
            ProgressStatus::Completed => $total,
        };
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
