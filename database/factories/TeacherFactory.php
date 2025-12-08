<?php

namespace Database\Factories;

use App\Domains\Teacher\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'first_name' => fake('uk_UA')->firstName(),
            'last_name' => fake('uk_UA')->lastName(),
            'middle_name' => fake()->optional()->firstName(),
            'position' => fake()->optional()->jobTitle(),
            'workplace' => fake()->optional()->company(),
            'specialization' => fake()->optional()->sentence(3),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
