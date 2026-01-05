<?php

namespace Database\Factories;

use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentQuizAttempt>
 */
class StudentQuizAttemptFactory extends Factory
{
    protected $model = StudentQuizAttempt::class;

    public function definition(): array
    {
        $maxScore = fake()->randomElement([10, 20, 30, 50, 100]);
        $score = fake()->numberBetween(0, $maxScore);
        $passingScore = 70;
        $passed = ($score / $maxScore) * 100 >= $passingScore;
        $startedAt = fake()->dateTimeBetween('-3 months', '-1 hour');

        return [
            'student_id' => Student::factory(),
            'quiz_id' => Quiz::factory(),
            'attempt_number' => 1,
            'score' => $score,
            'max_score' => $maxScore,
            'passed' => $passed,
            'answers' => [],
            'time_spent_seconds' => fake()->numberBetween(60, 3600),
            'started_at' => $startedAt,
            'completed_at' => fake()->dateTimeBetween($startedAt, 'now'),
        ];
    }

    public function passed(): static
    {
        return $this->state(function (array $attributes) {
            $maxScore = $attributes['max_score'] ?? 100;
            $score = fake()->numberBetween((int) ($maxScore * 0.7), $maxScore);

            return [
                'score' => $score,
                'passed' => true,
            ];
        });
    }

    public function failed(): static
    {
        return $this->state(function (array $attributes) {
            $maxScore = $attributes['max_score'] ?? 100;
            $score = fake()->numberBetween(0, (int) ($maxScore * 0.69));

            return [
                'score' => $score,
                'passed' => false,
            ];
        });
    }

    public function attempt(int $number): static
    {
        return $this->state(fn (array $attributes) => [
            'attempt_number' => $number,
        ]);
    }

    public function withAnswers(array $answers): static
    {
        return $this->state(fn (array $attributes) => [
            'answers' => $answers,
        ]);
    }
}
