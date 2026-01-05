<?php

namespace Database\Seeders;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseStudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();
        $teachers = User::whereNotIn('email', ['admin@lse-addiction.loc'])->get();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('Seeder skipped: No students or courses found.');

            return;
        }

        $statuses = ['active', 'completed'];
        $enrollments = [];

        foreach ($students->take(20) as $index => $student) {
            $coursesToEnroll = $courses->random(rand(1, 3));

            foreach ($coursesToEnroll as $course) {
                $status = $statuses[array_rand($statuses)];
                $totalLessons = rand(10, 20);
                $lessonsCompleted = $status === 'completed' ? $totalLessons : rand(0, $totalLessons - 1);

                $enrollments[] = [
                    'course_id' => $course->id,
                    'student_id' => $student->id,
                    'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                    'enrolled_at' => now()->subDays(rand(5, 60)),
                    'status' => $status,
                    'individual_discount' => rand(0, 3) === 0 ? rand(5, 20) : 0,
                    'lessons_completed' => $lessonsCompleted,
                    'total_lessons' => $totalLessons,
                    'last_activity_at' => now()->subDays(rand(0, 14)),
                    'notes' => null,
                ];
            }
        }

        $uniqueEnrollments = collect($enrollments)->unique(function ($item) {
            return $item['course_id'].'-'.$item['student_id'];
        })->values()->all();

        foreach ($uniqueEnrollments as $enrollment) {
            DB::table('course_student')->insert($enrollment);
        }

        $this->command->info('Created '.count($uniqueEnrollments).' course enrollments');

        $this->enrollTestStudent($teachers);
    }

    private function enrollTestStudent($teachers): void
    {
        $testStudent = Student::where('email', 'an.zhovna@gmail.com')->first();

        if (!$testStudent) {
            return;
        }

        $testEnrollments = [];

        $activeCourse = Course::where('status', CourseStatus::Active->value)->first();
        if ($activeCourse) {
            $testEnrollments[] = [
                'course_id' => $activeCourse->id,
                'student_id' => $testStudent->id,
                'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                'enrolled_at' => now()->subDays(30),
                'status' => 'completed',
                'individual_discount' => 0,
                'lessons_completed' => 15,
                'total_lessons' => 15,
                'last_activity_at' => now()->subDays(2),
                'notes' => null,
            ];
        }

        $draftCourse = Course::where('status', CourseStatus::Draft->value)->first();
        if ($draftCourse) {
            $testEnrollments[] = [
                'course_id' => $draftCourse->id,
                'student_id' => $testStudent->id,
                'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                'enrolled_at' => now()->subDays(5),
                'status' => 'active',
                'individual_discount' => 0,
                'lessons_completed' => 0,
                'total_lessons' => 10,
                'last_activity_at' => null,
                'notes' => null,
            ];
        }

        $archivedCourse = Course::where('status', CourseStatus::Archived->value)->first();
        if ($archivedCourse) {
            $testEnrollments[] = [
                'course_id' => $archivedCourse->id,
                'student_id' => $testStudent->id,
                'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                'enrolled_at' => now()->subMonths(5),
                'status' => 'active',
                'individual_discount' => 10,
                'lessons_completed' => 8,
                'total_lessons' => 12,
                'last_activity_at' => now()->subMonths(4),
                'notes' => null,
            ];
        }

        $hiddenCourse = Course::where('status', CourseStatus::Hidden->value)->first();
        if ($hiddenCourse) {
            $testEnrollments[] = [
                'course_id' => $hiddenCourse->id,
                'student_id' => $testStudent->id,
                'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                'enrolled_at' => now()->subDays(15),
                'status' => 'active',
                'individual_discount' => 0,
                'lessons_completed' => 3,
                'total_lessons' => 8,
                'last_activity_at' => now()->subDays(10),
                'notes' => null,
            ];
        }

        foreach ($testEnrollments as $enrollment) {
            $exists = DB::table('course_student')
                ->where('course_id', $enrollment['course_id'])
                ->where('student_id', $enrollment['student_id'])
                ->exists();

            if (!$exists) {
                DB::table('course_student')->insert($enrollment);
            }
        }

        $this->command->info('Enrolled an.zhovna@gmail.com in '.count($testEnrollments).' courses (all statuses)');
    }
}
