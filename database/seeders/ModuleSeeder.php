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
                'has_final_test' => false,
            ],
            [
                'name' => 'Теоретичні основи',
                'description' => 'Глибоке вивчення теоретичних аспектів та наукової бази.',
                'unlock_rule' => ModuleUnlockRule::CompletePrevious,
                'has_final_test' => true,
            ],
            [
                'name' => 'Практичне застосування',
                'description' => 'Практичні навички, клінічні випадки та реальні приклади застосування.',
                'unlock_rule' => ModuleUnlockRule::CompleteTest,
                'has_final_test' => true,
            ],
        ];

        $totalModules = 0;

        foreach ($courses as $course) {
            foreach ($moduleTemplates as $order => $template) {
                Module::create([
                    'course_id' => $course->id,
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'order' => $order + 1,
                    'status' => ModuleStatus::Active,
                    'has_final_test' => $template['has_final_test'],
                    'unlock_rule' => $template['unlock_rule'],
                ]);
                $totalModules++;
            }
        }

        $this->command->info("Created {$totalModules} modules for {$courses->count()} courses");
    }
}
