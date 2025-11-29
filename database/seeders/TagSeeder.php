<?php

namespace Database\Seeders;

use App\Domains\Course\Models\CourseTag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Кардіологія',
            'Неврологія',
            'Хірургія',
            'Педіатрія',
            'Онкологія',
            'Ендокринологія',
            'Анестезіологія',
            'Радіологія',
            'Психіатрія',
            'Інфекційні хвороби',
        ];

        foreach ($tags as $tagName) {
            CourseTag::findOrCreateByName($tagName);
        }

        $this->command->info('Created 10 medical tags');
    }
}
