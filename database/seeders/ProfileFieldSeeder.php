<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileFieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            [
                'key' => 'education_level',
                'type' => 'select',
                'label' => 'Ступінь освіти',
                'options' => json_encode([
                    'bachelor' => 'Бакалавр',
                    'specialist' => 'Спеціаліст',
                    'master' => 'Магістр',
                    'phd' => 'Аспірант/PhD',
                    'candidate' => 'Кандидат наук',
                    'doctor' => 'Доктор наук',
                ]),
                'is_required' => false,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'institution',
                'type' => 'text',
                'label' => 'Навчальний заклад',
                'options' => null,
                'is_required' => false,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'faculty',
                'type' => 'text',
                'label' => 'Факультет',
                'options' => null,
                'is_required' => false,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'graduation_year',
                'type' => 'number',
                'label' => 'Рік закінчення',
                'options' => null,
                'is_required' => false,
                'order' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'specialty',
                'type' => 'tags',
                'label' => 'Спеціальність',
                'options' => json_encode(['max_items' => 5]),
                'is_required' => false,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'diploma_number',
                'type' => 'text',
                'label' => 'Серія та номер диплому',
                'options' => null,
                'is_required' => false,
                'order' => 6,
                'is_active' => true,
            ],
            [
                'key' => 'workplace',
                'type' => 'text',
                'label' => 'Місце роботи',
                'options' => null,
                'is_required' => false,
                'order' => 7,
                'is_active' => true,
            ],
            [
                'key' => 'position',
                'type' => 'text',
                'label' => 'Посада',
                'options' => null,
                'is_required' => false,
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($fields as $field) {
            DB::table('profile_fields')->insert(array_merge($field, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
