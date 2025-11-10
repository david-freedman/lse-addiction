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
    }
}
