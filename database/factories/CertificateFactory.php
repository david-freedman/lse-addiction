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
            'certificate_number' => sprintf('%d-2556-0000000-%s', now()->year, str_pad((string) fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT)),
            'student_id' => Student::query()->inRandomOrder()->first()?->id ?? 1,
            'course_id' => Course::query()->inRandomOrder()->first()?->id ?? 1,
            'grade' => fake()->randomFloat(2, 60, 100),
            'study_hours' => fake()->numberBetween(60, 1000),
            'issued_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'viewed_at' => null,
            'issued_by' => null,
            'published_at' => null,
            'published_by' => null,
            'revoked_at' => null,
            'revoked_by' => null,
        ];
    }

    public function forStudent(Student $student): static
    {
        return $this->state(fn (array $attributes) => [
            'student_id' => $student->id,
            'certificate_number' => isset($attributes['course_id'])
                ? Certificate::generateNumber(Course::find($attributes['course_id']), $student)
                : $attributes['certificate_number'],
        ]);
    }

    public function forCourse(Course $course): static
    {
        return $this->state(fn (array $attributes) => [
            'course_id' => $course->id,
            'certificate_number' => isset($attributes['student_id'])
                ? Certificate::generateNumber($course, Student::find($attributes['student_id']))
                : $attributes['certificate_number'],
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

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
            'published_by' => null,
            'revoked_at' => null,
            'revoked_by' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => now(),
            'published_by' => null,
            'revoked_at' => null,
            'revoked_by' => null,
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'revoked_at' => now(),
            'revoked_by' => null,
        ]);
    }
}
