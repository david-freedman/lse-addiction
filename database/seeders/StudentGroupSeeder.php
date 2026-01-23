<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentGroupSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $courses = Course::take(3)->get();
        $students = Student::all();

        if (!$admin || $courses->isEmpty() || $students->isEmpty()) {
            $this->command->warn('Skipping StudentGroupSeeder: missing admin, courses, or students');

            return;
        }

        $groups = [
            [
                'name' => 'Група А - Початківці',
                'description' => 'Група для студентів, які тільки почали навчання',
                'course_id' => $courses->first()?->id,
            ],
            [
                'name' => 'Група Б - Просунуті',
                'description' => 'Група для студентів з досвідом',
                'course_id' => $courses->skip(1)->first()?->id,
            ],
            [
                'name' => 'VIP Група',
                'description' => 'Група для VIP студентів з індивідуальним підходом',
                'course_id' => null,
            ],
            [
                'name' => 'Тестова група',
                'description' => 'Група для тестування нових функцій',
                'course_id' => $courses->last()?->id,
            ],
        ];

        $createdGroups = 0;
        $addedMembers = 0;

        foreach ($groups as $groupData) {
            $group = StudentGroup::firstOrCreate(
                ['name' => $groupData['name']],
                [
                    'description' => $groupData['description'],
                    'course_id' => $groupData['course_id'],
                    'created_by' => $admin->id,
                ]
            );

            $createdGroups++;

            $randomStudents = $students->random(min(5, $students->count()));
            foreach ($randomStudents as $student) {
                if (!$group->students()->where('student_id', $student->id)->exists()) {
                    $group->students()->attach($student->id, ['added_at' => now()]);
                    $addedMembers++;
                }
            }
        }

        $this->command->info("Created {$createdGroups} student groups with {$addedMembers} members");
    }
}
