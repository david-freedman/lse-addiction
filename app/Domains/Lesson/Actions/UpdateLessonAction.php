<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Homework\Actions\CreateHomeworkAction;
use App\Domains\Homework\Actions\DeleteHomeworkAction;
use App\Domains\Homework\Actions\UpdateHomeworkAction;
use App\Domains\Homework\Data\CreateHomeworkData;
use App\Domains\Homework\Data\UpdateHomeworkData;
use App\Domains\Homework\Enums\HomeworkResponseType;
use App\Domains\Lesson\Data\UpdateLessonData;
use App\Domains\Lesson\Enums\DicomSourceType;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Actions\DeleteQuizAction;
use Illuminate\Support\Facades\Storage;

class UpdateLessonAction
{
    public static function execute(Lesson $lesson, UpdateLessonData $data): Lesson
    {
        $oldType = $lesson->type;
        $newType = $data->type ?? $oldType;

        $updateData = ['name' => $data->name];

        if ($data->description !== null) {
            $updateData['description'] = $data->description;
        }

        if ($data->content !== null) {
            $updateData['content'] = $data->content;
        }

        if ($data->type !== null) {
            $updateData['type'] = $data->type;
        }

        if ($data->video_url !== null) {
            $updateData['video_url'] = $data->video_url;
        }

        if ($data->qa_session_url !== null) {
            $updateData['qa_session_url'] = $data->qa_session_url;
        }

        if ($data->duration_minutes !== null) {
            $updateData['duration_minutes'] = $data->duration_minutes;
        }

        if ($data->order !== null) {
            $updateData['order'] = $data->order;
        }

        if ($data->status !== null) {
            $updateData['status'] = $data->status;
        }

        if ($data->is_downloadable !== null) {
            $updateData['is_downloadable'] = $data->is_downloadable;
        }

        if ($data->attachments !== null) {
            $updateData['attachments'] = $data->attachments;
        }

        $lesson->update($updateData);

        if ($newType === LessonType::Dicom) {
            self::handleDicomData($lesson, $data);
        } elseif ($oldType === LessonType::Dicom && $newType !== LessonType::Dicom) {
            self::clearDicomData($lesson);
        }

        self::handleQuizTypeChange($lesson, $oldType, $newType, $data);
        self::handleHomeworkChange($lesson, $data);

        return $lesson->fresh();
    }

    private static function handleQuizTypeChange(
        Lesson $lesson,
        LessonType $oldType,
        LessonType $newType,
        UpdateLessonData $data
    ): void {
        $wasQuizLike = in_array($oldType, [LessonType::Quiz, LessonType::Survey]);
        $isQuiz = $newType === LessonType::Quiz;
        $isSurvey = $newType === LessonType::Survey;
        $isQuizLike = $isQuiz || $isSurvey;

        if ($wasQuizLike && !$isQuizLike && $lesson->quiz) {
            DeleteQuizAction::execute($lesson->quiz);
            return;
        }

        if (!$wasQuizLike && $isQuizLike) {
            $lesson->quiz()->create([
                'title' => $lesson->name,
                'passing_score' => $isSurvey ? 0 : ($data->quiz_passing_score ?? 70),
                'max_attempts' => $data->quiz_max_attempts,
                'time_limit_minutes' => $data->quiz_time_limit_minutes,
                'show_correct_answers' => $isSurvey ? false : ($data->quiz_show_correct_answers ?? true),
                'is_final' => $isSurvey ? false : ($data->quiz_is_final ?? false),
                'is_survey' => $isSurvey,
            ]);
            return;
        }

        if ($wasQuizLike && $isQuizLike && $lesson->quiz) {
            $quizUpdateData = ['is_survey' => $isSurvey];

            if (!$isSurvey) {
                if ($data->quiz_passing_score !== null) {
                    $quizUpdateData['passing_score'] = $data->quiz_passing_score;
                }
                if ($data->quiz_show_correct_answers !== null) {
                    $quizUpdateData['show_correct_answers'] = $data->quiz_show_correct_answers;
                }
                if ($data->quiz_is_final !== null) {
                    $quizUpdateData['is_final'] = $data->quiz_is_final;
                }
            } else {
                $quizUpdateData['passing_score'] = 0;
                $quizUpdateData['show_correct_answers'] = false;
                $quizUpdateData['is_final'] = false;
            }

            if ($data->quiz_max_attempts !== null) {
                $quizUpdateData['max_attempts'] = $data->quiz_max_attempts;
            }
            if ($data->quiz_time_limit_minutes !== null) {
                $quizUpdateData['time_limit_minutes'] = $data->quiz_time_limit_minutes;
            }

            if (!empty($quizUpdateData)) {
                $lesson->quiz->update($quizUpdateData);
            }
        }
    }

    private static function handleHomeworkChange(Lesson $lesson, UpdateLessonData $data): void
    {
        $hasHomework = $lesson->homework !== null;
        $wantsHomework = $data->has_homework ?? $hasHomework;

        if ($hasHomework && !$wantsHomework) {
            app(DeleteHomeworkAction::class)($lesson->homework);
            return;
        }

        if (!$hasHomework && $wantsHomework) {
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
            return;
        }

        if ($hasHomework && $wantsHomework) {
            app(UpdateHomeworkAction::class)($lesson->homework, new UpdateHomeworkData(
                description: $data->homework_description,
                response_type: $data->homework_response_type,
                max_points: $data->homework_max_points,
                passing_score: $data->homework_passing_score,
                max_attempts: $data->homework_max_attempts,
                deadline_at: $data->homework_deadline_at,
                is_required: $data->homework_is_required,
                allowed_extensions: $data->homework_allowed_extensions,
                max_file_size_mb: $data->homework_max_file_size_mb,
                max_files: $data->homework_max_files,
            ));
        }
    }

    private static function handleDicomData(Lesson $lesson, UpdateLessonData $data): void
    {
        if ($data->dicom_file_upload) {
            UploadDicomFileAction::execute($lesson, $data->dicom_file_upload);
        } elseif ($data->dicom_url !== null) {
            if ($lesson->dicom_file_path) {
                Storage::disk('private')->delete($lesson->dicom_file_path);
            }
            $lesson->update([
                'dicom_source_type' => DicomSourceType::Url,
                'dicom_url' => $data->dicom_url,
                'dicom_file_path' => null,
                'dicom_metadata' => null,
            ]);
        }
    }

    private static function clearDicomData(Lesson $lesson): void
    {
        if ($lesson->dicom_file_path) {
            Storage::disk('private')->delete($lesson->dicom_file_path);
        }
        $lesson->update([
            'dicom_source_type' => null,
            'dicom_file_path' => null,
            'dicom_url' => null,
            'dicom_metadata' => null,
        ]);
    }
}
