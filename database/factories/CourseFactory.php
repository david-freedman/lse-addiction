<?php

namespace Database\Factories;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Enums\CourseType;
use App\Domains\Course\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $name = fake()->sentence(4);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'number' => str_pad((string) fake()->unique()->numberBetween(1000000, 9999999), 7, '0', STR_PAD_LEFT),
            'description' => fake()->paragraphs(3, true),
            'description_short' => fake()->optional()->sentence(10),
            'price' => fake()->randomFloat(2, 500, 10000),
            'old_price' => fake()->optional()->randomFloat(2, 5000, 15000),
            'discount_percentage' => fake()->optional()->numberBetween(10, 50),
            'teacher_id' => TeacherFactory::new(),
            'author_id' => UserFactory::new(),
            'banner' => null,
            'status' => fake()->randomElement(CourseStatus::cases()),
            'type' => fake()->randomElement(CourseType::cases()),
            'starts_at' => fake()->optional()->dateTimeBetween('now', '+3 months'),
            'label' => fake()->optional()->randomElement(['NEW', 'BESTSELLER', 'SALE']),
            'is_sequential' => fake()->boolean(70),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseStatus::Active,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseStatus::Draft,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseStatus::Archived,
        ]);
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0,
            'old_price' => null,
            'discount_percentage' => null,
            'type' => CourseType::Free,
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => CourseType::Upcoming,
            'starts_at' => fake()->dateTimeBetween('now', '+3 months'),
        ]);
    }
}
