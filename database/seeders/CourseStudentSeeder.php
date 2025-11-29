<?php

namespace Database\Seeders;

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

        $statuses = ['active', 'completed', 'paused'];
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
                    'last_activity_at' => $status !== 'paused' ? now()->subDays(rand(0, 14)) : null,
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
    }
}
