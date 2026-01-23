<?php

namespace Database\Seeders;

use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\ActivityLog\Models\ActivityLog;
use App\Domains\Course\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseHistorySeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::query()
            ->with(['modules.lessons.quiz'])
            ->first();

        if (! $course) {
            $this->command->warn('No courses found, skipping CourseHistorySeeder');

            return;
        }

        $admins = User::query()->pluck('id')->toArray();

        if (empty($admins)) {
            $this->command->warn('No admin users found, skipping CourseHistorySeeder');

            return;
        }

        $modules = $course->modules;
        $firstModule = $modules->first();
        $secondModule = $modules->skip(1)->first() ?? $firstModule;
        $firstLesson = $firstModule?->lessons->first();
        $secondLesson = $firstModule?->lessons->skip(1)->first() ?? $firstLesson;
        $quiz = $firstLesson?->quiz;

        $entries = $this->buildEntries($course, $admins, $modules, $firstModule, $secondModule, $firstLesson, $secondLesson, $quiz);
        $totalEntries = count($entries);

        foreach ($entries as $index => $entry) {
            ActivityLog::create(array_merge($entry, [
                'course_id' => $course->id,
                'ip_address' => '192.168.1.'.rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'created_at' => now()->subDays($totalEntries - $index)->subHours(rand(1, 12)),
            ]));
        }

        $this->command->info("Created {$totalEntries} course history entries for \"{$course->name}\"");
    }

    private function buildEntries(
        Course $course,
        array $admins,
        $modules,
        $firstModule,
        $secondModule,
        $firstLesson,
        $secondLesson,
        $quiz,
    ): array {
        $entries = [];

        $entries[] = [
            'performed_by' => $admins[array_rand($admins)],
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CourseCreated,
            'description' => "Створено курс \"{$course->name}\"",
            'properties' => [
                'attributes' => [
                    'name' => $course->name,
                    'number' => $course->number,
                    'price' => $course->price,
                    'status' => 'draft',
                    'type' => $course->type?->value ?? 'standard',
                    'is_sequential' => false,
                ],
            ],
        ];

        $entries[] = [
            'performed_by' => $admins[array_rand($admins)],
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CourseUpdated,
            'description' => "Оновлено курс \"{$course->name}\"",
            'properties' => [
                'changes' => [
                    'name' => ['old' => 'Чернетка курсу', 'new' => $course->name],
                    'price' => ['old' => 0, 'new' => $course->price],
                    'status' => ['old' => 'draft', 'new' => 'active'],
                ],
            ],
        ];

        $entries[] = [
            'performed_by' => $admins[array_rand($admins)],
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CourseUpdated,
            'description' => "Оновлено теги курсу \"{$course->name}\"",
            'properties' => [
                'changes' => [
                    'tags' => [
                        'added' => ['адикції', 'психотерапія', 'клінічна практика'],
                        'removed' => [],
                    ],
                ],
            ],
        ];

        $entries[] = [
            'performed_by' => $admins[array_rand($admins)],
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CourseUpdated,
            'description' => "Оновлено курс \"{$course->name}\"",
            'properties' => [
                'changes' => [
                    'description' => [
                        'old' => 'Короткий опис курсу з адиктології',
                        'new' => 'Поглиблений курс з адиктології для лікарів та психологів, що охоплює сучасні підходи до лікування залежностей.',
                    ],
                    'tags' => [
                        'added' => ['доказова медицина'],
                        'removed' => ['клінічна практика'],
                    ],
                ],
            ],
        ];

        $entries[] = [
            'performed_by' => $admins[array_rand($admins)],
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CourseUpdated,
            'description' => "Оновлено курс \"{$course->name}\"",
            'properties' => [
                'changes' => [
                    'is_sequential' => ['old' => false, 'new' => true],
                    'requires_certificate_approval' => ['old' => false, 'new' => true],
                ],
            ],
        ];

        if ($firstModule) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $firstModule->id,
                'activity_type' => ActivityType::ModuleCreated,
                'description' => "Створено модуль \"{$firstModule->name}\"",
                'properties' => [
                    'attributes' => [
                        'name' => $firstModule->name,
                        'order' => 1,
                        'unlock_rule' => 'sequential',
                    ],
                ],
            ];

            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $firstModule->id,
                'activity_type' => ActivityType::ModuleUpdated,
                'description' => "Оновлено модуль \"{$firstModule->name}\"",
                'properties' => [
                    'changes' => [
                        'name' => ['old' => 'Модуль 1', 'new' => $firstModule->name],
                        'unlock_rule' => ['old' => 'free', 'new' => 'sequential'],
                    ],
                ],
            ];
        }

        if ($secondModule) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $secondModule->id,
                'activity_type' => ActivityType::ModuleCreated,
                'description' => "Створено модуль \"{$secondModule->name}\"",
                'properties' => [
                    'attributes' => [
                        'name' => $secondModule->name,
                        'order' => 2,
                        'unlock_rule' => 'sequential',
                    ],
                ],
            ];
        }

        if ($modules->count() >= 2) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Course,
                'subject_id' => $course->id,
                'activity_type' => ActivityType::ModulesReordered,
                'description' => "Пересортовано модулі курсу \"{$course->name}\"",
                'properties' => [
                    'reorder' => [
                        'old' => $modules->map(fn ($m) => ['id' => $m->id, 'name' => $m->name])->values()->toArray(),
                        'new' => $modules->reverse()->map(fn ($m) => ['id' => $m->id, 'name' => $m->name])->values()->toArray(),
                    ],
                ],
            ];
        }

        if ($firstLesson) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Lesson,
                'subject_id' => $firstLesson->id,
                'activity_type' => ActivityType::LessonCreated,
                'description' => "Створено урок \"{$firstLesson->name}\"",
                'properties' => [
                    'attributes' => [
                        'name' => $firstLesson->name,
                        'order' => 1,
                        'duration_minutes' => $firstLesson->duration_minutes ?? 45,
                    ],
                ],
            ];

            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Lesson,
                'subject_id' => $firstLesson->id,
                'activity_type' => ActivityType::LessonUpdated,
                'description' => "Оновлено урок \"{$firstLesson->name}\"",
                'properties' => [
                    'changes' => [
                        'name' => ['old' => 'Урок 1', 'new' => $firstLesson->name],
                        'video_url' => ['old' => null, 'new' => 'https://vimeo.com/123456789'],
                        'duration_minutes' => ['old' => 30, 'new' => $firstLesson->duration_minutes ?? 45],
                    ],
                ],
            ];
        }

        if ($secondLesson && $firstModule && $firstModule->lessons->count() >= 2) {
            $lessons = $firstModule->lessons;

            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $firstModule->id,
                'activity_type' => ActivityType::LessonsReordered,
                'description' => "Пересортовано уроки модуля \"{$firstModule->name}\"",
                'properties' => [
                    'reorder' => [
                        'old' => $lessons->map(fn ($l) => ['id' => $l->id, 'name' => $l->name])->values()->toArray(),
                        'new' => $lessons->reverse()->map(fn ($l) => ['id' => $l->id, 'name' => $l->name])->values()->toArray(),
                    ],
                ],
            ];
        }

        if ($quiz) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Quiz,
                'subject_id' => $quiz->id,
                'activity_type' => ActivityType::QuizCreated,
                'description' => "Створено квіз для уроку \"{$firstLesson->name}\"",
                'properties' => [
                    'attributes' => [
                        'passing_score' => $quiz->passing_score ?? 70,
                        'max_attempts' => $quiz->max_attempts ?? 3,
                        'time_limit_minutes' => $quiz->time_limit_minutes ?? 30,
                        'is_final' => false,
                    ],
                ],
            ];

            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Quiz,
                'subject_id' => $quiz->id,
                'activity_type' => ActivityType::QuizUpdated,
                'description' => "Оновлено квіз для уроку \"{$firstLesson->name}\"",
                'properties' => [
                    'changes' => [
                        'passing_score' => ['old' => 60, 'new' => $quiz->passing_score ?? 70],
                        'max_attempts' => ['old' => 5, 'new' => $quiz->max_attempts ?? 3],
                        'time_limit_minutes' => ['old' => null, 'new' => $quiz->time_limit_minutes ?? 30],
                    ],
                ],
            ];

            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Quiz,
                'subject_id' => $quiz->id,
                'activity_type' => ActivityType::QuizQuestionCreated,
                'description' => 'Додано питання до квізу',
                'properties' => [
                    'questions' => [
                        'added' => [
                            ['text' => 'Які основні ознаки алкогольної залежності за DSM-5?'],
                            ['text' => 'Який механізм дії налтрексону при лікуванні опіоїдної залежності?'],
                        ],
                    ],
                ],
            ];

            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Quiz,
                'subject_id' => $quiz->id,
                'activity_type' => ActivityType::QuizQuestionUpdated,
                'description' => 'Оновлено питання квізу',
                'properties' => [
                    'questions' => [
                        'updated' => [
                            [
                                'id' => 1,
                                'changes' => [
                                    'question_text' => [
                                        'old' => 'Назвіть ознаки залежності',
                                        'new' => 'Які основні ознаки алкогольної залежності за DSM-5?',
                                    ],
                                    'points' => ['old' => 5, 'new' => 10],
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Quiz,
                'subject_id' => $quiz->id,
                'activity_type' => ActivityType::QuizQuestionDeleted,
                'description' => 'Видалено питання з квізу',
                'properties' => [
                    'questions' => [
                        'removed' => [
                            ['text' => 'Тестове питання для видалення'],
                        ],
                    ],
                ],
            ];
        }

        if ($secondLesson) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Lesson,
                'subject_id' => $secondLesson->id,
                'activity_type' => ActivityType::LessonDeleted,
                'description' => 'Видалено урок "Старий урок"',
                'properties' => [
                    'attributes' => [
                        'name' => 'Старий урок з фармакотерапії',
                        'order' => 5,
                        'duration_minutes' => 60,
                    ],
                ],
            ];
        }

        if ($secondModule) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Module,
                'subject_id' => $secondModule->id,
                'activity_type' => ActivityType::ModuleDeleted,
                'description' => 'Видалено модуль "Застарілий модуль"',
                'properties' => [
                    'attributes' => [
                        'name' => 'Застарілий модуль з діагностики',
                        'order' => 3,
                    ],
                ],
            ];
        }

        if ($quiz) {
            $entries[] = [
                'performed_by' => $admins[array_rand($admins)],
                'subject_type' => ActivitySubject::Quiz,
                'subject_id' => $quiz->id,
                'activity_type' => ActivityType::QuizDeleted,
                'description' => 'Видалено квіз',
                'properties' => [
                    'attributes' => [
                        'passing_score' => 50,
                        'max_attempts' => 2,
                        'is_final' => false,
                    ],
                ],
            ];
        }

        $entries[] = [
            'performed_by' => $admins[array_rand($admins)],
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CourseUpdated,
            'description' => "Оновлено курс \"{$course->name}\"",
            'properties' => [
                'changes' => [
                    'old_price' => ['old' => null, 'new' => 5000],
                    'discount_percentage' => ['old' => null, 'new' => 20],
                    'label' => ['old' => null, 'new' => 'popular'],
                ],
            ],
        ];

        return $entries;
    }
}
