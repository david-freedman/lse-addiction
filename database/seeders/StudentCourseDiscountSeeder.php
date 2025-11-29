<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Discount\Enums\DiscountType;
use App\Domains\Discount\Models\StudentCourseDiscount;
use App\Domains\Student\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentCourseDiscountSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();
        $admin = User::where('role', 'admin')->first();

        if ($students->isEmpty() || $courses->isEmpty() || !$admin) {
            $this->command->warn('Seeder skipped: No students, courses, or admin found.');

            return;
        }

        $discountsData = [
            ['type' => DiscountType::Percentage, 'value' => 10],
            ['type' => DiscountType::Percentage, 'value' => 15],
            ['type' => DiscountType::Percentage, 'value' => 20],
            ['type' => DiscountType::Percentage, 'value' => 25],
            ['type' => DiscountType::Fixed, 'value' => 500],
            ['type' => DiscountType::Fixed, 'value' => 1000],
            ['type' => DiscountType::Percentage, 'value' => 50],
            ['type' => DiscountType::Fixed, 'value' => 200],
        ];

        $count = 0;
        foreach ($students->take(8) as $index => $student) {
            $course = $courses->random();
            $discountData = $discountsData[$index];

            $isUsed = $index < 3;

            StudentCourseDiscount::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'type' => $discountData['type'],
                'value' => $discountData['value'],
                'assigned_by' => $admin->id,
                'used_at' => $isUsed ? now()->subDays(rand(1, 30)) : null,
            ]);

            $count++;
        }

        $this->command->info("Created {$count} student course discounts");
    }
}
