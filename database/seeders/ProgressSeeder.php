<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Progress\Models\StudentModuleProgress;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProgressSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::whereHas('courses')->with('courses')->get();

        $courseProgressCount = 0;
        $moduleProgressCount = 0;
        $lessonProgressCount = 0;
        $quizAttemptCount = 0;
        $surveyAttemptCount = 0;

        foreach ($students as $student) {
            foreach ($student->courses as $course) {
                $startedAt = Carbon::parse($course->pivot->enrolled_at);

                $progressPercentage = fake()->randomElement([0, 25, 50, 75, 100]);
                $status = match (true) {
                    $progressPercentage === 0 => ProgressStatus::NotStarted,
                    $progressPercentage === 100 => ProgressStatus::Completed,
                    default => ProgressStatus::InProgress,
                };

                StudentCourseProgress::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => $status,
                    'progress_percentage' => $progressPercentage,
                    'started_at' => $status !== ProgressStatus::NotStarted ? $startedAt : null,
                    'completed_at' => $status === ProgressStatus::Completed
                        ? $startedAt->copy()->addDays(fake()->numberBetween(7, 60))
                        : null,
                ]);
                $courseProgressCount++;

                DB::table('course_student')
                    ->where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->update(['status' => $status === ProgressStatus::Completed ? 'completed' : 'active']);

                if ($status === ProgressStatus::NotStarted) {
                    continue;
                }

                $modules = Module::where('course_id', $course->id)->orderBy('order')->get();
                $totalModules = $modules->count();
                $modulesToComplete = (int) ceil($totalModules * ($progressPercentage / 100));

                foreach ($modules as $moduleIndex => $module) {
                    $moduleStartedAt = $startedAt->copy()->addDays($moduleIndex * 7);

                    if ($moduleIndex < $modulesToComplete - 1) {
                        $moduleStatus = ProgressStatus::Completed;
                    } elseif ($moduleIndex === $modulesToComplete - 1 && $progressPercentage < 100) {
                        $moduleStatus = ProgressStatus::InProgress;
                    } elseif ($moduleIndex < $modulesToComplete) {
                        $moduleStatus = ProgressStatus::Completed;
                    } else {
                        $moduleStatus = ProgressStatus::NotStarted;
                    }

                    $lessons = Lesson::where('module_id', $module->id)->orderBy('order')->get();
                    $totalLessons = $lessons->count();
                    $lessonsCompleted = match ($moduleStatus) {
                        ProgressStatus::Completed => $totalLessons,
                        ProgressStatus::InProgress => fake()->numberBetween(1, max(1, $totalLessons - 1)),
                        ProgressStatus::NotStarted => 0,
                    };

                    StudentModuleProgress::create([
                        'student_id' => $student->id,
                        'module_id' => $module->id,
                        'status' => $moduleStatus,
                        'lessons_completed' => $lessonsCompleted,
                        'total_lessons' => $totalLessons,
                        'progress_percentage' => $totalLessons > 0
                            ? round(($lessonsCompleted / $totalLessons) * 100, 2)
                            : 0,
                        'started_at' => $moduleStatus !== ProgressStatus::NotStarted ? $moduleStartedAt : null,
                        'completed_at' => $moduleStatus === ProgressStatus::Completed
                            ? $moduleStartedAt->copy()->addDays(fake()->numberBetween(3, 14))
                            : null,
                    ]);
                    $moduleProgressCount++;

                    foreach ($lessons as $lessonIndex => $lesson) {
                        if ($lessonIndex >= $lessonsCompleted && $moduleStatus !== ProgressStatus::NotStarted) {
                            if ($lessonIndex === $lessonsCompleted) {
                                $lessonStatus = ProgressStatus::InProgress;
                            } else {
                                continue;
                            }
                        } elseif ($lessonIndex < $lessonsCompleted) {
                            $lessonStatus = ProgressStatus::Completed;
                        } else {
                            continue;
                        }

                        $lessonStartedAt = $moduleStartedAt->copy()->addDays($lessonIndex);

                        StudentLessonProgress::create([
                            'student_id' => $student->id,
                            'lesson_id' => $lesson->id,
                            'status' => $lessonStatus,
                            'started_at' => $lessonStartedAt,
                            'completed_at' => $lessonStatus === ProgressStatus::Completed
                                ? $lessonStartedAt->copy()->addHours(fake()->numberBetween(1, 24))
                                : null,
                        ]);
                        $lessonProgressCount++;

                        if ($lessonStatus === ProgressStatus::Completed
                            && $lesson->type === LessonType::Survey
                            && $lesson->quiz
                        ) {
                            $surveyAttemptCount += $this->createSurveyAttempt(
                                $student,
                                $lesson->quiz,
                                $lessonStartedAt
                            );
                        }

                        if ($lessonStatus === ProgressStatus::Completed
                            && $lesson->type === LessonType::Quiz
                            && $lesson->quiz
                            && !$lesson->is_final
                        ) {
                            $quizAttemptCount += $this->createQuizAttemptForLesson(
                                $student,
                                $lesson->quiz,
                                $lessonStartedAt
                            );
                        }
                    }

                    if ($moduleStatus === ProgressStatus::Completed && $module->hasFinalTest()) {
                        $finalTestLesson = $module->finalTestLesson();
                        $quiz = $finalTestLesson?->quiz;

                        if ($quiz) {
                            $attempts = fake()->numberBetween(1, min(3, $quiz->max_attempts ?? 3));
                            for ($attemptNum = 1; $attemptNum <= $attempts; $attemptNum++) {
                                $maxScore = $quiz->questions()->sum('points') ?: 10;
                                $isPassed = $attemptNum === $attempts;
                                $score = $isPassed
                                    ? fake()->numberBetween((int) ($maxScore * 0.7), $maxScore)
                                    : fake()->numberBetween(0, (int) ($maxScore * 0.69));

                                $attemptStartedAt = $moduleStartedAt->copy()
                                    ->addDays(fake()->numberBetween(5, 10))
                                    ->addHours($attemptNum - 1);

                                StudentQuizAttempt::create([
                                    'student_id' => $student->id,
                                    'quiz_id' => $quiz->id,
                                    'attempt_number' => $attemptNum,
                                    'score' => $score,
                                    'max_score' => $maxScore,
                                    'passed' => $isPassed,
                                    'answers' => [],
                                    'time_spent_seconds' => fake()->numberBetween(300, 1800),
                                    'started_at' => $attemptStartedAt,
                                    'completed_at' => $attemptStartedAt->copy()->addMinutes(fake()->numberBetween(10, 30)),
                                ]);
                                $quizAttemptCount++;
                            }
                        }
                    }
                }
            }
        }

        $this->command->info("Created progress records:");
        $this->command->info("  - Course progress: {$courseProgressCount}");
        $this->command->info("  - Module progress: {$moduleProgressCount}");
        $this->command->info("  - Lesson progress: {$lessonProgressCount}");
        $this->command->info("  - Quiz attempts: {$quizAttemptCount}");
        $this->command->info("  - Survey attempts: {$surveyAttemptCount}");

        $this->ensureTestStudentHasCompletedProgress();
    }

    private function ensureTestStudentHasCompletedProgress(): void
    {
        $testStudent = Student::where('email', 'an.zhovna@gmail.com')->first();

        if (!$testStudent) {
            return;
        }

        $hasCompleted = StudentCourseProgress::where('student_id', $testStudent->id)
            ->where('status', ProgressStatus::Completed)
            ->exists();

        if ($hasCompleted) {
            return;
        }

        $progress = StudentCourseProgress::where('student_id', $testStudent->id)->first();

        if ($progress) {
            $progress->update([
                'status' => ProgressStatus::Completed,
                'progress_percentage' => 100,
                'completed_at' => now()->subDays(5),
            ]);

            $this->command->info('Ensured an.zhovna@gmail.com has completed progress for certificate');
        }
    }

    private function createSurveyAttempt(Student $student, Quiz $quiz, Carbon $lessonStartedAt): int
    {
        $existingAttempt = StudentQuizAttempt::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->exists();

        if ($existingAttempt) {
            return 0;
        }

        $questions = $quiz->questions()->with('answers')->get();

        if ($questions->isEmpty()) {
            return 0;
        }

        $answers = [];
        foreach ($questions as $question) {
            $questionAnswers = $question->answers;
            if ($questionAnswers->isEmpty()) {
                continue;
            }

            $randomAnswer = $questionAnswers->random();
            $answers[$question->id] = [
                'selected' => [$randomAnswer->id],
            ];
        }

        $attemptStartedAt = $lessonStartedAt->copy()->addHours(fake()->numberBetween(1, 12));

        StudentQuizAttempt::create([
            'student_id' => $student->id,
            'quiz_id' => $quiz->id,
            'attempt_number' => 1,
            'score' => 0,
            'max_score' => 0,
            'passed' => true,
            'answers' => $answers,
            'time_spent_seconds' => fake()->numberBetween(60, 300),
            'started_at' => $attemptStartedAt,
            'completed_at' => $attemptStartedAt->copy()->addMinutes(fake()->numberBetween(2, 10)),
        ]);

        return 1;
    }

    private function createQuizAttemptForLesson(Student $student, Quiz $quiz, Carbon $lessonStartedAt): int
    {
        $existingAttempt = StudentQuizAttempt::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->exists();

        if ($existingAttempt) {
            return 0;
        }

        $questions = $quiz->questions()->with('answers')->get();

        if ($questions->isEmpty()) {
            return 0;
        }

        $maxScore = $questions->sum('points') ?: 10;
        $passingScore = $quiz->passing_score ?? 70;

        $score = fake()->numberBetween(
            (int) ($maxScore * $passingScore / 100),
            $maxScore
        );

        $answers = [];
        foreach ($questions as $question) {
            $questionAnswers = $question->answers;
            if ($questionAnswers->isEmpty()) {
                continue;
            }

            $correctAnswer = $questionAnswers->firstWhere('is_correct', true);
            $selectedAnswer = $correctAnswer ?? $questionAnswers->random();

            $answers[$question->id] = [
                'selected' => [$selectedAnswer->id],
            ];
        }

        $attemptStartedAt = $lessonStartedAt->copy()->addHours(fake()->numberBetween(1, 12));

        StudentQuizAttempt::create([
            'student_id' => $student->id,
            'quiz_id' => $quiz->id,
            'attempt_number' => 1,
            'score' => $score,
            'max_score' => $maxScore,
            'passed' => true,
            'answers' => $answers,
            'time_spent_seconds' => fake()->numberBetween(300, 900),
            'started_at' => $attemptStartedAt,
            'completed_at' => $attemptStartedAt->copy()->addMinutes(fake()->numberBetween(5, 15)),
        ]);

        return 1;
    }
}
