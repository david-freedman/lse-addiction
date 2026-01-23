<?php

namespace Database\Seeders;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Enums\ModuleStatus;
use App\Domains\Module\Enums\ModuleUnlockRule;
use App\Domains\Module\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        $moduleTemplates = [
            [
                'name' => 'Вступ до курсу',
                'description' => 'Знайомство з основними концепціями та завданнями курсу.',
                'unlock_rule' => ModuleUnlockRule::None,
            ],
            [
                'name' => 'Теоретичні основи',
                'description' => 'Глибоке вивчення теоретичних аспектів та наукової бази.',
                'unlock_rule' => ModuleUnlockRule::CompletePrevious,
            ],
            [
                'name' => 'Практичне застосування',
                'description' => 'Практичні навички, клінічні випадки та реальні приклади застосування.',
                'unlock_rule' => ModuleUnlockRule::CompleteTest,
            ],
        ];

        $totalModules = 0;

        foreach ($courses as $course) {
            foreach ($moduleTemplates as $order => $template) {
                Module::create([
                    'course_id' => $course->id,
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'order' => $order,
                    'status' => ModuleStatus::Active,
                    'unlock_rule' => $template['unlock_rule'],
                ]);
                $totalModules++;
            }
        }

        $this->command->info("Created {$totalModules} modules for {$courses->count()} courses");
    }
}
