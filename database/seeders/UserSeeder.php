<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Майборода Алла Анатоліївна',
            'email' => 'maiboroda@lse.loc',
            'password' => Hash::make('password'),
            'photo' => 'img/speakers/01.webp',
            'position' => 'Лікар-рентгенолог',
        ]);

        User::create([
            'name' => 'Чумак Ярослав Анатолійович',
            'email' => 'chumak@lse.loc',
            'password' => Hash::make('password'),
            'photo' => 'img/speakers/02.webp',
            'position' => 'Лікар-рентгенолог',
        ]);

        User::create([
            'name' => 'Петренко Олександр Іванович',
            'email' => 'petrenko@lse.loc',
            'password' => Hash::make('password'),
            'photo' => 'img/speakers/03.webp',
            'position' => 'Кардіолог',
        ]);

        User::create([
            'name' => 'Сидоренко Ірина Василівна',
            'email' => 'sydorenko@lse.loc',
            'password' => Hash::make('password'),
            'photo' => 'img/speakers/04.webp',
            'position' => 'Невролог',
        ]);

        User::create([
            'name' => 'Коваленко Микола Петрович',
            'email' => 'kovalenko@lse.loc',
            'password' => Hash::make('password'),
            'photo' => 'img/speakers/05.webp',
            'position' => 'Хірург',
        ]);

        User::create([
            'name' => 'Іваненко Тетяна Сергіївна',
            'email' => 'ivanenko@lse.loc',
            'password' => Hash::make('password'),
            'photo' => 'img/speakers/06.webp',
            'position' => 'Педіатр',
        ]);
    }
}
