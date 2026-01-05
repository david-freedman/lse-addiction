<?php

namespace Database\Seeders;

use App\Domains\Lesson\Enums\DicomSourceType;
use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $modules = Module::with('course')->get();

        $lessonTemplates = [
            [
                'name' => 'Введення та огляд теми',
                'description' => 'Огляд основних понять та структури розділу.',
                'type' => LessonType::Video,
                'duration_minutes' => 15,
            ],
            [
                'name' => 'Основні концепції',
                'description' => 'Детальне пояснення ключових концепцій та термінології.',
                'type' => LessonType::Text,
                'duration_minutes' => null,
            ],
            [
                'name' => 'Клінічні приклади',
                'description' => 'Розбір реальних клінічних випадків та практичних ситуацій.',
                'type' => LessonType::Video,
                'duration_minutes' => 25,
            ],
            [
                'name' => 'Практичні рекомендації',
                'description' => 'Конкретні алгоритми дій та рекомендації для клінічної практики.',
                'type' => LessonType::Text,
                'duration_minutes' => null,
            ],
            [
                'name' => 'Перевірка знань',
                'description' => 'Тестування засвоєного матеріалу.',
                'type' => LessonType::Quiz,
                'duration_minutes' => 10,
            ],
            [
                'name' => 'Аналіз медичних зображень',
                'description' => 'Інтерактивний перегляд та аналіз діагностичних знімків.',
                'type' => LessonType::Dicom,
                'duration_minutes' => 15,
                'dicom_source_type' => DicomSourceType::File,
                'dicom_file_path' => 'dicom/dwv-test-simple.dcm',
                'dicom_metadata' => ['modality' => 'MG', 'is_multiframe' => false, 'frame_count' => 1],
            ],
            [
                'name' => 'Практичний розбір знімків',
                'description' => 'Детальний розбір діагностичних зображень з коментарями.',
                'type' => LessonType::Dicom,
                'duration_minutes' => 20,
                'dicom_source_type' => DicomSourceType::File,
                'dicom_file_path' => 'dicom/bbmri-53323131.dcm',
                'dicom_metadata' => ['modality' => 'MR', 'is_multiframe' => false, 'frame_count' => 1],
            ],
            [
                'name' => 'Q&A сесія з викладачем',
                'description' => 'Інтерактивна сесія питань та відповідей з можливістю обговорення складних випадків.',
                'type' => LessonType::QaSession,
                'duration_minutes' => 60,
                'qa_session_url' => 'https://zoom.us/j/123456789',
            ],
            [
                'name' => 'Опитування студентів',
                'description' => 'Опитування для зворотного зв\'язку та оцінки засвоєння матеріалу.',
                'type' => LessonType::Survey,
                'duration_minutes' => 5,
            ],
        ];

        $videoUrls = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://vimeo.com/123456789',
            'https://www.youtube.com/watch?v=jNQXAC9IVRw',
        ];

        $textContent = '<h2>Основний зміст уроку</h2>
<p>Цей урок охоплює важливі аспекти теми, які необхідно засвоїти для успішного проходження курсу.</p>
<h3>Ключові моменти</h3>
<ul>
<li>Перший важливий аспект теми</li>
<li>Другий ключовий момент для запам\'ятовування</li>
<li>Третій пункт, що потребує особливої уваги</li>
</ul>
<h3>Практичне застосування</h3>
<p>Застосування отриманих знань у клінічній практиці є ключовим для розвитку професійних навичок.</p>';

        $totalLessons = 0;

        foreach ($modules as $module) {
            foreach ($lessonTemplates as $order => $template) {
                $lessonData = [
                    'module_id' => $module->id,
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'type' => $template['type'],
                    'duration_minutes' => $template['duration_minutes'],
                    'order' => $order + 1,
                    'status' => LessonStatus::Published,
                    'attachments' => null,
                ];

                if ($template['type'] === LessonType::Video) {
                    $lessonData['video_url'] = $videoUrls[array_rand($videoUrls)];
                    $lessonData['content'] = null;
                } elseif ($template['type'] === LessonType::Text) {
                    $lessonData['content'] = $textContent;
                    $lessonData['video_url'] = null;
                } elseif ($template['type'] === LessonType::Dicom) {
                    $lessonData['dicom_source_type'] = $template['dicom_source_type'];
                    $lessonData['dicom_url'] = $template['dicom_url'] ?? null;
                    $lessonData['dicom_file_path'] = $template['dicom_file_path'] ?? null;
                    $lessonData['dicom_metadata'] = $template['dicom_metadata'] ?? null;
                    $lessonData['content'] = null;
                    $lessonData['video_url'] = null;
                } elseif ($template['type'] === LessonType::QaSession) {
                    $lessonData['qa_session_url'] = $template['qa_session_url'] ?? null;
                    $lessonData['content'] = null;
                    $lessonData['video_url'] = null;
                } elseif ($template['type'] === LessonType::Survey) {
                    $lessonData['content'] = null;
                    $lessonData['video_url'] = null;
                } else {
                    $lessonData['content'] = null;
                    $lessonData['video_url'] = null;
                }

                Lesson::create($lessonData);
                $totalLessons++;
            }
        }

        $this->command->info("Created {$totalLessons} lessons for {$modules->count()} modules");
    }
}
