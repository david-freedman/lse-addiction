<?php

namespace Database\Seeders;

use App\Domains\Teacher\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereNotIn('email', ['admin@lse-addiction.loc'])->get();

        $teacherData = [
            [
                'first_name' => 'Алла',
                'last_name' => 'Майборода',
                'middle_name' => 'Анатоліївна',
                'position' => 'Професор',
                'workplace' => 'Національний медичний університет ім. О.О. Богомольця',
                'specialization' => 'Кардіологія',
                'description' => 'Доктор медичних наук, професор кафедри внутрішньої медицини. Автор понад 150 наукових праць з кардіології та внутрішньої медицини.',
            ],
            [
                'first_name' => 'Ярослав',
                'last_name' => 'Чумак',
                'middle_name' => 'Анатолійович',
                'position' => 'Доцент',
                'workplace' => 'Львівський національний медичний університет',
                'specialization' => 'Неврологія',
                'description' => 'Кандидат медичних наук, доцент кафедри неврології. Спеціалізується на лікуванні цереброваскулярних захворювань.',
            ],
            [
                'first_name' => 'Олександр',
                'last_name' => 'Петренко',
                'middle_name' => 'Іванович',
                'position' => 'Завідувач відділення',
                'workplace' => 'Київська міська клінічна лікарня №1',
                'specialization' => 'Хірургія',
                'description' => 'Хірург вищої категорії, керівник відділення малоінвазивної хірургії. Провів понад 2000 лапароскопічних операцій.',
            ],
            [
                'first_name' => 'Ірина',
                'last_name' => 'Сидоренко',
                'middle_name' => 'Василівна',
                'position' => 'Професор',
                'workplace' => 'Одеський національний медичний університет',
                'specialization' => 'Педіатрія',
                'description' => 'Доктор медичних наук, професор кафедри педіатрії. Експерт з дитячої пульмонології та алергології.',
            ],
            [
                'first_name' => 'Микола',
                'last_name' => 'Коваленко',
                'middle_name' => 'Петрович',
                'position' => 'Головний лікар',
                'workplace' => 'Національний інститут раку',
                'specialization' => 'Онкологія',
                'description' => 'Онколог вищої категорії, доктор медичних наук. Спеціалізується на лікуванні пухлин шлунково-кишкового тракту.',
            ],
            [
                'first_name' => 'Тетяна',
                'last_name' => 'Іваненко',
                'middle_name' => 'Сергіївна',
                'position' => 'Доцент',
                'workplace' => 'Харківський національний медичний університет',
                'specialization' => 'Ендокринологія',
                'description' => 'Кандидат медичних наук, ендокринолог вищої категорії. Автор методичних рекомендацій з лікування цукрового діабету.',
            ],
        ];

        foreach ($users as $index => $user) {
            if (!isset($teacherData[$index])) {
                break;
            }

            Teacher::create([
                'user_id' => $user->id,
                'first_name' => $teacherData[$index]['first_name'],
                'last_name' => $teacherData[$index]['last_name'],
                'middle_name' => $teacherData[$index]['middle_name'],
                'position' => $teacherData[$index]['position'],
                'workplace' => $teacherData[$index]['workplace'],
                'specialization' => $teacherData[$index]['specialization'],
                'description' => $teacherData[$index]['description'],
            ]);
        }

        $this->command->info('Created 6 teachers');
    }
}
