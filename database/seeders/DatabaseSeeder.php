<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ProfileFieldSeeder::class,
            UserSeeder::class,
            TeacherSeeder::class,
            TagSeeder::class,
            CourseSeeder::class,
            CourseTagSeeder::class,
            StudentSeeder::class,
            CourseStudentSeeder::class,
            AdminUserSeeder::class,
            StudentCourseDiscountSeeder::class,
            TransactionSeeder::class,
            FAQSeeder::class,
        ]);
    }
}
