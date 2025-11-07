<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $coach = User::first();
        $author = User::first();

        Course::create([
            'name' => 'Дегенеративні захворювання хребта',
            'description' => 'Дегенеративні захворювання хребта — це група патологій, пов\'язаних із віковими змінами або травмами хребетного стовпа. До них належать остеоартроз, спондилоартроз, грижі міжхребцевих дисків, стеноз хребетного каналу та інші. Ці захворювання призводять до болю, обмеження рухливості та можуть вплинути на якість життя. Вчасна діагностика та лікування допомагають уповільнити прогресування хвороби та полегшити симптоми.',
            'price' => 1500.00,
            'coach_id' => $coach?->id,
            'author_id' => $author?->id,
            'status' => 'published',
            'type' => 'upcoming',
            'starts_at' => now()->addDays(3),
            'label' => 'онлайн майстер-клас',
            'image' => 'courses/01.webp',
        ]);

        Course::create([
            'name' => 'Календар майбутніх подій',
            'description' => 'Переглянайте розклад майбутніх вебінарів, тренінгів та освітніх заходів. Плануйте свій навчальний шлях та не пропускайте важливі події.',
            'price' => 0.00,
            'coach_id' => $coach?->id,
            'author_id' => $author?->id,
            'status' => 'published',
            'type' => 'free',
            'starts_at' => now()->addDays(5),
            'label' => 'безкоштовний вебінар',
            'image' => 'courses/02.webp',
        ]);

        Course::create([
            'name' => 'Каталог вебінарів в записі',
            'description' => 'Доступ до бібліотеки записаних вебінарів. Навчайтеся у зручний для вас час та переглядайте матеріали необмежену кількість разів.',
            'price' => 800.00,
            'coach_id' => $coach?->id,
            'author_id' => $author?->id,
            'status' => 'published',
            'type' => 'recorded',
            'starts_at' => now()->subDays(10),
            'label' => 'курс у запісі',
            'image' => 'courses/03.webp',
        ]);
    }
}
