<?php

namespace App\Domains\Homework\Actions;

use App\Domains\Homework\Data\HomeworkListFilterData;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Homework\Models\HomeworkSubmission;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportHomeworkResultsAction
{
    public function toExcel(HomeworkListFilterData $filters): StreamedResponse
    {
        $submissions = $this->getFilteredSubmissions($filters);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Курс',
            'Модуль',
            'Урок',
            'Студент',
            'Email',
            'Спроба',
            'Статус',
            'Оцінка',
            'Макс. балів',
            'Дата здачі',
            'Коментар викладача',
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValue([$col + 1, 1], $header);
        }

        $row = 2;
        foreach ($submissions as $submission) {
            $homework = $submission->homework;
            $lesson = $homework->lesson;
            $module = $lesson->module;
            $course = $module->course;

            $sheet->setCellValue([1, $row], $course->name);
            $sheet->setCellValue([2, $row], $module->name);
            $sheet->setCellValue([3, $row], $lesson->name);
            $sheet->setCellValue([4, $row], $submission->student?->full_name ?? '');
            $sheet->setCellValue([5, $row], $submission->student?->email?->value ?? '');
            $sheet->setCellValue([6, $row], $submission->attempt_number);
            $sheet->setCellValue([7, $row], $this->getStatusLabel($submission->status));
            $sheet->setCellValue([8, $row], $submission->score);
            $sheet->setCellValue([9, $row], $homework->max_points);
            $sheet->setCellValue([10, $row], $submission->submitted_at?->format('d.m.Y H:i'));
            $sheet->setCellValue([11, $row], $submission->feedback ?? '');
            $row++;
        }

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'homework-results-'.now()->format('Y-m-d').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return Collection<int, HomeworkSubmission>
     */
    private function getFilteredSubmissions(HomeworkListFilterData $filters): Collection
    {
        $query = HomeworkSubmission::query()
            ->with(['homework.lesson.module.course', 'student']);

        if ($filters->course_id) {
            $query->whereHas('homework.lesson.module', fn ($q) => $q->where('course_id', $filters->course_id));
        }

        if ($filters->module_id) {
            $query->whereHas('homework.lesson', fn ($q) => $q->where('module_id', $filters->module_id));
        }

        if ($filters->lesson_id) {
            $query->whereHas('homework', fn ($q) => $q->where('lesson_id', $filters->lesson_id));
        }

        if ($filters->has_pending) {
            $query->whereIn('status', [
                HomeworkSubmissionStatus::Pending,
                HomeworkSubmissionStatus::RevisionRequested,
            ]);
        }

        if ($filters->search) {
            $query->whereHas('homework.lesson', fn ($q) => $q->where('name', 'ilike', "%{$filters->search}%"));
        }

        return $query
            ->orderBy(
                HomeworkSubmission::query()
                    ->select('courses.name')
                    ->join('homeworks', 'homeworks.id', '=', 'homework_submissions.homework_id')
                    ->join('lessons', 'lessons.id', '=', 'homeworks.lesson_id')
                    ->join('modules', 'modules.id', '=', 'lessons.module_id')
                    ->join('courses', 'courses.id', '=', 'modules.course_id')
                    ->whereColumn('homework_submissions.id', 'homework_submissions.id')
                    ->limit(1)
            )
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    private function getStatusLabel(HomeworkSubmissionStatus $status): string
    {
        return match ($status) {
            HomeworkSubmissionStatus::Pending => 'На перевірці',
            HomeworkSubmissionStatus::Approved => 'Зараховано',
            HomeworkSubmissionStatus::Rejected => 'Не зараховано',
            HomeworkSubmissionStatus::RevisionRequested => 'На доопрацювання',
        };
    }
}
