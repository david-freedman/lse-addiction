<?php

namespace Database\Factories;

use App\Domains\Teacher\Models\Teacher;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Webinar>
 */
class WebinarFactory extends Factory
{
    protected $model = Webinar::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'teacher_id' => Teacher::factory(),
            'starts_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'duration_minutes' => fake()->randomElement([60, 90, 120, 180]),
            'status' => WebinarStatus::Draft,
            'max_participants' => fake()->randomElement([null, 50, 100, 200]),
            'price' => fake()->randomElement([0, 500, 1000, 2000]),
            'old_price' => fake()->optional(0.3)->randomElement([1000, 2000, 3000]),
            'banner' => null,
            'meeting_url' => null,
            'recording_url' => null,
        ];
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebinarStatus::Upcoming,
            'starts_at' => fake()->dateTimeBetween('+1 day', '+2 weeks'),
        ]);
    }

    public function live(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebinarStatus::Live,
            'starts_at' => now()->subMinutes(30),
        ]);
    }

    public function ended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebinarStatus::Ended,
            'starts_at' => fake()->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    public function recorded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebinarStatus::Recorded,
            'starts_at' => fake()->dateTimeBetween('-1 month', '-1 day'),
            'recording_url' => 'https://www.youtube.com/watch?v=' . fake()->regexify('[a-zA-Z0-9]{11}'),
        ]);
    }

    public function startingSoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebinarStatus::Upcoming,
            'starts_at' => now()->addMinutes(15),
        ]);
    }
}
