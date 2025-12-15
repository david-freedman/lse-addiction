<?php

namespace Database\Factories;

use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        return [
            'certificate_number' => Certificate::generateNumber(),
            'student_id' => Student::query()->inRandomOrder()->first()?->id ?? 1,
            'course_id' => Course::query()->inRandomOrder()->first()?->id ?? 1,
            'grade' => fake()->randomFloat(2, 60, 100),
            'study_hours' => fake()->numberBetween(60, 1000),
            'issued_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'viewed_at' => null,
            'issued_by' => null,
        ];
    }

    public function forStudent(Student $student): static
    {
        return $this->state(fn (array $attributes) => [
            'student_id' => $student->id,
        ]);
    }

    public function forCourse(Course $course): static
    {
        return $this->state(fn (array $attributes) => [
            'course_id' => $course->id,
        ]);
    }

    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => fake()->randomFloat(2, 90, 100),
        ]);
    }

    public function good(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => fake()->randomFloat(2, 75, 89.99),
        ]);
    }

    public function satisfactory(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => fake()->randomFloat(2, 60, 74.99),
        ]);
    }
}
