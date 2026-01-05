<?php

namespace Database\Seeders;

use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Майборода Алла Анатоліївна',
            'email' => 'maiboroda@lse.loc',
            'photo' => 'https://ui-avatars.com/api/?name=Майборода+Алла&background=14b8a6&color=fff&size=256',
            'role' => UserRole::Teacher,
        ]);

        User::create([
            'name' => 'Чумак Ярослав Анатолійович',
            'email' => 'chumak@lse.loc',
            'photo' => 'https://ui-avatars.com/api/?name=Чумак+Ярослав&background=0891b2&color=fff&size=256',
            'role' => UserRole::Teacher,
        ]);

        User::create([
            'name' => 'Петренко Олександр Іванович',
            'email' => 'petrenko@lse.loc',
            'photo' => 'https://ui-avatars.com/api/?name=Петренко+Олександр&background=4f46e5&color=fff&size=256',
            'role' => UserRole::Teacher,
        ]);

        User::create([
            'name' => 'Сидоренко Ірина Василівна',
            'email' => 'sydorenko@lse.loc',
            'photo' => 'https://ui-avatars.com/api/?name=Сидоренко+Ірина&background=059669&color=fff&size=256',
            'role' => UserRole::Teacher,
        ]);

        User::create([
            'name' => 'Коваленко Микола Петрович',
            'email' => 'kovalenko@lse.loc',
            'photo' => 'https://ui-avatars.com/api/?name=Коваленко+Микола&background=dc2626&color=fff&size=256',
            'role' => UserRole::Teacher,
        ]);

        User::create([
            'name' => 'Іваненко Тетяна Сергіївна',
            'email' => 'ivanenko@lse.loc',
            'photo' => 'https://ui-avatars.com/api/?name=Іваненко+Тетяна&background=7c3aed&color=fff&size=256',
            'role' => UserRole::Teacher,
        ]);
    }
}
