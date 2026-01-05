<?php

namespace Database\Factories;

use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\Homework;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Student\Models\Student;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkSubmissionFactory extends Factory
{
    protected $model = HomeworkSubmission::class;

    public function definition(): array
    {
        return [
            'homework_id' => null,
            'student_id' => null,
            'attempt_number' => 1,
            'text_response' => fake()->optional()->paragraphs(3, true),
            'files' => null,
            'status' => HomeworkSubmissionStatus::Pending,
            'is_late' => fake()->boolean(10),
            'score' => null,
            'feedback' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'submitted_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => HomeworkSubmissionStatus::Pending,
            'score' => null,
            'feedback' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => HomeworkSubmissionStatus::Approved,
            'score' => fake()->numberBetween(7, 10),
            'feedback' => fake()->optional()->sentence(),
            'reviewed_by' => \App\Models\User::query()->inRandomOrder()->first()?->id,
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => HomeworkSubmissionStatus::Rejected,
            'score' => fake()->numberBetween(0, 4),
            'feedback' => fake()->sentence(),
            'reviewed_by' => \App\Models\User::query()->inRandomOrder()->first()?->id,
            'reviewed_at' => now(),
        ]);
    }

    public function revisionRequested(): static
    {
        return $this->state(fn () => [
            'status' => HomeworkSubmissionStatus::RevisionRequested,
            'score' => null,
            'feedback' => fake()->sentence(),
            'reviewed_by' => \App\Models\User::query()->inRandomOrder()->first()?->id,
            'reviewed_at' => now(),
        ]);
    }

    public function late(): static
    {
        return $this->state(fn () => ['is_late' => true]);
    }
}
