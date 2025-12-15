<?php

namespace App\Applications\Http\Student\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\DicomSourceType;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class StreamDicomController
{
    public function __invoke(Request $request, Course $course, Lesson $lesson): StreamedResponse|JsonResponse
    {
        $student = $request->user();

        if (!$student->hasAccessToCourse($course)) {
            abort(403, 'Ви не маєте доступу до цього курсу');
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        if ($lesson->type !== LessonType::Dicom) {
            abort(404, 'Цей урок не є DICOM');
        }

        if ($lesson->dicom_source_type === DicomSourceType::Url) {
            return response()->json([
                'type' => 'url',
                'url' => $lesson->dicom_url,
                'metadata' => $lesson->dicom_metadata,
            ]);
        }

        if (!$lesson->dicom_file_path || !Storage::disk('private')->exists($lesson->dicom_file_path)) {
            abort(404, 'DICOM файл не знайдено');
        }

        return Storage::disk('private')->response($lesson->dicom_file_path, null, [
            'Content-Type' => 'application/dicom',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
