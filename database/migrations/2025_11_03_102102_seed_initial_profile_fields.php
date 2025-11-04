<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
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
                'type' => 'text',
                'label' => 'Спеціальність',
                'options' => null,
                'is_required' => false,
                'order' => 5,
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

    public function down(): void
    {
        DB::table('profile_fields')->whereIn('key', [
            'education_level',
            'institution',
            'faculty',
            'graduation_year',
            'specialty',
        ])->delete();
    }
};
