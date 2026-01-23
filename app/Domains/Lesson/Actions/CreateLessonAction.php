<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Homework\Actions\CreateHomeworkAction;
use App\Domains\Homework\Data\CreateHomeworkData;
use App\Domains\Homework\Enums\HomeworkResponseType;
use App\Domains\Lesson\Data\CreateLessonData;
use App\Domains\Lesson\Enums\DicomSourceType;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Actions\CreateQuizForLessonAction;

class CreateLessonAction
{
    public static function execute(Module $module, CreateLessonData $data): Lesson
    {
        $isFinal = $data->is_final ?? false;

        if ($isFinal && $module->hasFinalTest()) {
            throw new \InvalidArgumentException('Модуль вже має підсумковий тест');
        }

        $order = self::determineOrder($module, $data, $isFinal);

        $lesson = $module->lessons()->create([
            'name' => $data->name,
            'description' => $data->description,
            'content' => $data->content,
            'type' => $data->type,
            'video_url' => $data->video_url,
            'qa_session_url' => $data->qa_session_url,
            'duration_minutes' => $data->duration_minutes,
            'order' => $order,
            'status' => $data->status,
            'attachments' => $data->attachments,
            'is_final' => $isFinal,
            'allow_retake_after_pass' => $data->allow_retake_after_pass ?? true,
        ]);

        if ($data->type === LessonType::Dicom) {
            self::handleDicomData($lesson, $data);
        }

        if ($data->type === LessonType::Quiz) {
            CreateQuizForLessonAction::execute($lesson, $data, isSurvey: false);
        }

        if ($data->type === LessonType::Survey) {
            CreateQuizForLessonAction::execute($lesson, $data, isSurvey: true);
        }

        if ($data->has_homework) {
            app(CreateHomeworkAction::class)($lesson, new CreateHomeworkData(
                description: $data->homework_description,
                response_type: $data->homework_response_type ?? HomeworkResponseType::Both,
                max_points: $data->homework_max_points ?? 10,
                passing_score: $data->homework_passing_score,
                max_attempts: $data->homework_max_attempts,
                deadline_at: $data->homework_deadline_at,
                is_required: $data->homework_is_required ?? false,
                allowed_extensions: $data->homework_allowed_extensions,
                max_file_size_mb: $data->homework_max_file_size_mb ?? 10,
                max_files: $data->homework_max_files ?? 5,
            ));
        }

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Lesson,
            'subject_id' => $lesson->id,
            'activity_type' => ActivityType::LessonCreated,
            'description' => 'Lesson created',
            'properties' => [
                'attributes' => array_filter([
                    'name' => $data->name,
                    'type' => $data->type?->value,
                    'status' => $data->status,
                    'is_final' => $isFinal,
                ], fn ($v) => $v !== null),
            ],
            'course_id' => $module->course_id,
        ]));

        return $lesson;
    }

    private static function handleDicomData(Lesson $lesson, CreateLessonData $data): void
    {
        if ($data->dicom_file_upload) {
            UploadDicomFileAction::execute($lesson, $data->dicom_file_upload);
        } elseif ($data->dicom_url) {
            $lesson->update([
                'dicom_source_type' => DicomSourceType::Url,
                'dicom_url' => $data->dicom_url,
                'dicom_file_path' => null,
                'dicom_metadata' => null,
            ]);
        }
    }

    private static function determineOrder(Module $module, CreateLessonData $data, bool $isFinal): int
    {
        if ($isFinal) {
            return ($module->lessons()->max('order') ?? -1) + 1;
        }

        if ($data->order !== null) {
            return $data->order;
        }

        $finalTest = $module->finalTestLesson();
        if ($finalTest) {
            return $finalTest->order;
        }

        return $module->lessons()->count();
    }
}
