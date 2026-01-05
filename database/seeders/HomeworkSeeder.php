<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Homework\Enums\HomeworkResponseType;
use App\Domains\Homework\Models\Homework;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Database\Seeder;

class HomeworkSeeder extends Seeder
{
    public function run(): void
    {
        $homeworkCount = 0;
        $submissionCount = 0;

        $lessons = Lesson::whereIn('type', [LessonType::Video, LessonType::Text])
            ->whereDoesntHave('homework')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        foreach ($lessons as $lesson) {
            $homework = Homework::factory()
                ->state([
                    'lesson_id' => $lesson->id,
                    'response_type' => fake()->randomElement(HomeworkResponseType::cases()),
                    'is_required' => fake()->boolean(30),
                ])
                ->create();

            $homeworkCount++;

            $course = $lesson->module->course;
            $students = $course->students()->inRandomOrder()->limit(5)->get();

            foreach ($students as $student) {
                $attemptCount = fake()->numberBetween(1, 2);

                for ($attempt = 1; $attempt <= $attemptCount; $attempt++) {
                    $state = fake()->randomElement(['pending', 'approved', 'rejected', 'revisionRequested']);

                    $submission = HomeworkSubmission::factory()
                        ->{$state}()
                        ->state([
                            'homework_id' => $homework->id,
                            'student_id' => $student->id,
                            'attempt_number' => $attempt,
                            'text_response' => $homework->response_type->allowsText()
                                ? fake()->paragraphs(2, true)
                                : null,
                        ])
                        ->create();

                    $submissionCount++;
                }
            }
        }

        $this->command->info("Created {$homeworkCount} homeworks with {$submissionCount} submissions");
    }
}
