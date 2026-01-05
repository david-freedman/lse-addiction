<?php

namespace Database\Seeders;

use App\Domains\Teacher\Models\Teacher;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WebinarSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Skipping WebinarSeeder.');

            return;
        }

        Webinar::create([
            'title' => 'МРТ та КТ: візуалізація печінки',
            'slug' => Str::slug('mrt-ta-kt-vizualizatsiya-pechinky'),
            'description' => 'Сучасні методи візуалізації патології печінки. Розглянемо протоколи дослідження, типові та атипові знахідки при гепатоцелюлярній карциномі, метастатичному ураженні та доброякісних утвореннях. Практичні рекомендації з інтерпретації зображень.',
            'banner' => 'https://images.unsplash.com/photo-1516549655169-df83a0774514?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->addDays(9)->setTime(14, 0),
            'duration_minutes' => 90,
            'status' => WebinarStatus::Upcoming,
            'max_participants' => 100,
            'price' => 0,
        ]);

        Webinar::create([
            'title' => 'Дегенеративні захворювання хребта (частина 2)',
            'slug' => Str::slug('degeneratyvni-zakhvoryuvannya-khrebta-2'),
            'description' => 'Продовження курсу з діагностики та лікування дегенеративних захворювань хребта. Фокус на мініінвазивних методах лікування, реабілітації та профілактиці рецидивів. Розбір клінічних випадків.',
            'banner' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->addDays(18)->setTime(10, 0),
            'duration_minutes' => 180,
            'status' => WebinarStatus::Upcoming,
            'max_participants' => 50,
            'price' => 1500,
            'old_price' => 2000,
        ]);

        Webinar::create([
            'title' => 'Ультразвукова діагностика патології щитоподібної залози',
            'slug' => Str::slug('uzd-shchytopodibnoyi-zalozy'),
            'description' => 'Детальний огляд УЗД-семіотики захворювань щитоподібної залози. Класифікація вузлів за TI-RADS, показання до пункційної біопсії, типові помилки діагностики. Інтерактивний розбір зображень.',
            'banner' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->addDays(5)->setTime(16, 0),
            'duration_minutes' => 120,
            'status' => WebinarStatus::Upcoming,
            'max_participants' => 80,
            'price' => 1,
            'old_price' => 800,
        ]);

        Webinar::create([
            'title' => 'Кардіоваскулярна візуалізація: від ЕхоКГ до КТ-ангіографії',
            'slug' => Str::slug('kardiovaskulyarna-vizualizatsiya'),
            'description' => 'Комплексний підхід до візуалізації серця та судин. Порівняння можливостей різних методів дослідження, алгоритми вибору оптимальної модальності, інтерпретація результатів у клінічному контексті.',
            'banner' => 'https://images.unsplash.com/photo-1628348068343-c6a848d2b6dd?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->addDays(12)->setTime(11, 0),
            'duration_minutes' => 150,
            'status' => WebinarStatus::Upcoming,
            'max_participants' => 120,
            'price' => 1200,
            'old_price' => 1500,
        ]);

        Webinar::create([
            'title' => 'Рентгенодіагностика захворювань органів грудної клітки',
            'slug' => Str::slug('rentgenodiagnostyka-ogk'),
            'description' => 'Систематичний підхід до аналізу рентгенограм грудної клітки. Нормальна рентгенанатомія, типові патологічні зміни при пневмоніях, туберкульозі, онкологічних захворюваннях. Рекомендації щодо формулювання висновків.',
            'banner' => 'https://images.unsplash.com/photo-1530497610245-94d3c16cda28?q=80&w=2064',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->addDays(25)->setTime(15, 0),
            'duration_minutes' => 90,
            'status' => WebinarStatus::Upcoming,
            'max_participants' => null,
            'price' => 500,
        ]);

        Webinar::create([
            'title' => 'Основи інтервенційної радіології (чернетка)',
            'slug' => Str::slug('osnovy-interventsiynoi-radiologii-draft'),
            'description' => 'Вступ до інтервенційної радіології: ендоваскулярні втручання, біопсії під візуальним контролем, дренування абсцесів. Матеріал у процесі підготовки.',
            'banner' => 'https://images.unsplash.com/photo-1551076805-e1869033e561?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->addDays(45)->setTime(10, 0),
            'duration_minutes' => 120,
            'status' => WebinarStatus::Draft,
            'max_participants' => 60,
            'price' => 1800,
        ]);

        Webinar::create([
            'title' => 'ПЕТ-КТ в онкології: практичні аспекти',
            'slug' => Str::slug('pet-kt-v-onkologii-completed'),
            'description' => 'Завершений вебінар з основ позитронно-емісійної томографії в онкологічній практиці. Показання, протоколи, інтерпретація результатів. Доступний запис.',
            'banner' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->subDays(14)->setTime(14, 0),
            'duration_minutes' => 90,
            'status' => WebinarStatus::Completed,
            'max_participants' => 100,
            'price' => 1000,
        ]);

        Webinar::create([
            'title' => 'Нейровізуалізація при інсульті (скасовано)',
            'slug' => Str::slug('neirovizualizatsiya-insult-cancelled'),
            'description' => 'Вебінар скасовано. Нові дати будуть оголошені пізніше.',
            'banner' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->addDays(7)->setTime(16, 0),
            'duration_minutes' => 120,
            'status' => WebinarStatus::Cancelled,
            'max_participants' => 80,
            'price' => 900,
        ]);

        Webinar::create([
            'title' => 'Онкологічна візуалізація: LIVE сесія',
            'slug' => Str::slug('onkologichna-vizualizatsiya-live'),
            'description' => 'Пряма трансляція з розбором клінічних випадків онкологічної візуалізації. Інтерактивна сесія з відповідями на запитання.',
            'banner' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070',
            'teacher_id' => $teachers->random()->id,
            'starts_at' => now()->subMinutes(30),
            'duration_minutes' => 120,
            'status' => WebinarStatus::Live,
            'max_participants' => 100,
            'price' => 800,
            'meeting_url' => 'https://zoom.us/j/1234567890',
        ]);

        $this->command->info('Created 9 webinars (5 Upcoming, 1 Draft, 1 Completed, 1 Cancelled, 1 Live)');
    }
}
