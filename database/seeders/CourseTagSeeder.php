<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseTag;
use Illuminate\Database\Seeder;

class CourseTagSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        $tags = CourseTag::all();

        if ($courses->isEmpty() || $tags->isEmpty()) {
            $this->command->warn('Seeder skipped: No courses or tags found.');

            return;
        }

        $courseTagMapping = [
            0 => ['Кардіологія'],
            1 => ['Неврологія', 'Ендокринологія'],
            2 => ['Хірургія', 'Анестезіологія'],
            3 => ['Педіатрія'],
            4 => ['Онкологія'],
            5 => ['Ендокринологія'],
            6 => ['Психіатрія', 'Неврологія'],
            7 => ['Анестезіологія', 'Хірургія'],
            8 => ['Радіологія', 'Неврологія'],
            9 => ['Інфекційні хвороби'],
        ];

        foreach ($courses as $index => $course) {
            if (! isset($courseTagMapping[$index])) {
                continue;
            }

            $tagNames = $courseTagMapping[$index];
            $tagIds = $tags->whereIn('name', $tagNames)->pluck('id')->toArray();

            $course->tags()->sync($tagIds);
        }

        $this->command->info('Attached tags to courses');
    }
}
