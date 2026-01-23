<?php

namespace Database\Factories;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Enums\ModuleStatus;
use App\Domains\Module\Enums\ModuleUnlockRule;
use App\Domains\Module\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Module>
 */
class ModuleFactory extends Factory
{
    protected $model = Module::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'order' => 0,
            'status' => ModuleStatus::Active,
            'unlock_rule' => ModuleUnlockRule::None,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ModuleStatus::Active,
        ]);
    }

    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ModuleStatus::Hidden,
        ]);
    }

    public function requiresPrevious(): static
    {
        return $this->state(fn (array $attributes) => [
            'unlock_rule' => ModuleUnlockRule::CompletePrevious,
        ]);
    }
}
