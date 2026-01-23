<?php

namespace Database\Seeders;

use App\Domains\Student\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Андрій', 'surname' => 'Жовна', 'email' => 'an.zhovna@gmail.com', 'phone' => '+380963877677', 'city' => 'Київ', 'birthday' => '1990-12-06', 'days_ago' => 0],
            ['name' => 'Олена', 'surname' => 'Бондаренко', 'email' => 'bondarenko@gmail.com', 'phone' => '+380501234501', 'city' => 'Київ', 'birthday' => '1990-03-15', 'days_ago' => 120],
            ['name' => 'Андрій', 'surname' => 'Шевченко', 'email' => 'shevchenko@gmail.com', 'phone' => '+380501234502', 'city' => 'Львів', 'birthday' => '1985-07-22', 'days_ago' => 90],
            ['name' => 'Марія', 'surname' => 'Коваленко', 'email' => 'kovalenko.m@gmail.com', 'phone' => '+380501234503', 'city' => 'Одеса', 'birthday' => '1992-11-08', 'days_ago' => 75],
            ['name' => 'Віктор', 'surname' => 'Мельник', 'email' => 'melnyk.v@gmail.com', 'phone' => '+380501234504', 'city' => 'Харків', 'birthday' => '1988-05-30', 'days_ago' => 60],
            ['name' => 'Наталія', 'surname' => 'Ткаченко', 'email' => 'tkachenko.n@gmail.com', 'phone' => '+380501234505', 'city' => 'Дніпро', 'birthday' => '1995-09-12', 'days_ago' => 45],
            ['name' => 'Олександр', 'surname' => 'Бойко', 'email' => 'boyko.o@gmail.com', 'phone' => '+380501234506', 'city' => 'Запоріжжя', 'birthday' => '1991-01-25', 'days_ago' => 40],
            ['name' => 'Ірина', 'surname' => 'Кравченко', 'email' => 'kravchenko.i@gmail.com', 'phone' => '+380501234507', 'city' => 'Вінниця', 'birthday' => '1987-12-03', 'days_ago' => 35],
            ['name' => 'Дмитро', 'surname' => 'Олійник', 'email' => 'oliynyk.d@gmail.com', 'phone' => '+380501234508', 'city' => 'Полтава', 'birthday' => '1993-06-18', 'days_ago' => 30],
            ['name' => 'Юлія', 'surname' => 'Лисенко', 'email' => 'lysenko.y@gmail.com', 'phone' => '+380501234509', 'city' => 'Черкаси', 'birthday' => '1989-04-07', 'days_ago' => 28],
            ['name' => 'Сергій', 'surname' => 'Морозенко', 'email' => 'morozenko.s@gmail.com', 'phone' => '+380501234510', 'city' => 'Суми', 'birthday' => '1994-08-21', 'days_ago' => 25],
            ['name' => 'Анна', 'surname' => 'Павленко', 'email' => 'pavlenko.a@gmail.com', 'phone' => '+380501234511', 'city' => 'Чернігів', 'birthday' => '1986-02-14', 'days_ago' => 22],
            ['name' => 'Максим', 'surname' => 'Гончаренко', 'email' => 'honcharenko.m@gmail.com', 'phone' => '+380501234512', 'city' => 'Житомир', 'birthday' => '1996-10-29', 'days_ago' => 20],
            ['name' => 'Катерина', 'surname' => 'Савченко', 'email' => 'savchenko.k@gmail.com', 'phone' => '+380501234513', 'city' => 'Рівне', 'birthday' => '1990-07-05', 'days_ago' => 18],
            ['name' => 'Ігор', 'surname' => 'Романенко', 'email' => 'romanenko.i@gmail.com', 'phone' => '+380501234514', 'city' => 'Луцьк', 'birthday' => '1984-11-17', 'days_ago' => 15],
            ['name' => 'Тетяна', 'surname' => 'Василенко', 'email' => 'vasylenko.t@gmail.com', 'phone' => '+380501234515', 'city' => 'Тернопіль', 'birthday' => '1997-03-26', 'days_ago' => 12],
            ['name' => 'Володимир', 'surname' => 'Зінченко', 'email' => 'zinchenko.v@gmail.com', 'phone' => '+380501234516', 'city' => 'Хмельницький', 'birthday' => '1992-09-09', 'days_ago' => 10],
            ['name' => 'Людмила', 'surname' => 'Кириленко', 'email' => 'kyrylenko.l@gmail.com', 'phone' => '+380501234517', 'city' => 'Івано-Франківськ', 'birthday' => '1988-12-31', 'days_ago' => 9],
            ['name' => 'Петро', 'surname' => 'Марченко', 'email' => 'marchenko.p@gmail.com', 'phone' => '+380501234518', 'city' => 'Ужгород', 'birthday' => '1995-06-02', 'days_ago' => 8],
            ['name' => 'Оксана', 'surname' => 'Яковенко', 'email' => 'yakovenko.o@gmail.com', 'phone' => '+380501234519', 'city' => 'Чернівці', 'birthday' => '1991-01-19', 'days_ago' => 7],
            ['name' => 'Роман', 'surname' => 'Федоренко', 'email' => 'fedorenko.r@gmail.com', 'phone' => '+380501234520', 'city' => 'Миколаїв', 'birthday' => '1993-04-11', 'days_ago' => 6],
            ['name' => 'Вікторія', 'surname' => 'Левченко', 'email' => 'levchenko.v@gmail.com', 'phone' => '+380501234521', 'city' => 'Херсон', 'birthday' => '1989-08-08', 'days_ago' => 5],
            ['name' => 'Артем', 'surname' => 'Семененко', 'email' => 'semenenko.a@gmail.com', 'phone' => '+380501234522', 'city' => 'Кропивницький', 'birthday' => '1994-05-23', 'days_ago' => 4],
            ['name' => 'Софія', 'surname' => 'Тимошенко', 'email' => 'tymoshenko.s@gmail.com', 'phone' => '+380501234523', 'city' => 'Біла Церква', 'birthday' => '1996-02-28', 'days_ago' => 3],
            ['name' => 'Микола', 'surname' => 'Пономаренко', 'email' => 'ponomarenko.m@gmail.com', 'phone' => '+380501234524', 'city' => 'Краматорськ', 'birthday' => '1987-10-14', 'days_ago' => 2],
            ['name' => 'Дарина', 'surname' => 'Гриценко', 'email' => 'hrytsenko.d@gmail.com', 'phone' => '+380501234525', 'city' => 'Маріуполь', 'birthday' => '1998-07-07', 'days_ago' => 1],
        ];

        foreach ($students as $data) {
            Student::create([
                'number' => Student::generateNumber(),
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'city' => $data['city'],
                'birthday' => $data['birthday'],
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'created_at' => now()->subDays($data['days_ago']),
                'updated_at' => now()->subDays($data['days_ago']),
            ]);
        }

        $this->command->info('Created 26 verified students');
    }
}
