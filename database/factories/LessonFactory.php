<?php

namespace Database\Factories;

use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $type = fake()->randomElement(LessonType::cases());

        return [
            'module_id' => Module::factory(),
            'name' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'content' => $type === LessonType::Text ? fake()->paragraphs(5, true) : null,
            'type' => $type,
            'video_url' => $type === LessonType::Video ? fake()->url() : null,
            'dicom_file' => null,
            'duration_minutes' => fake()->optional()->numberBetween(5, 90),
            'order' => 0,
            'status' => LessonStatus::Draft,
            'is_downloadable' => fake()->boolean(20),
            'attachments' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonStatus::Published,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonStatus::Draft,
        ]);
    }

    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => LessonType::Video,
            'video_url' => fake()->url(),
            'content' => null,
        ]);
    }

    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => LessonType::Text,
            'video_url' => null,
            'content' => fake()->paragraphs(5, true),
        ]);
    }

    public function quiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => LessonType::Quiz,
            'video_url' => null,
            'content' => null,
        ]);
    }
}
