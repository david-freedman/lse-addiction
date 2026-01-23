<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Quiz\Data\QuizResultsIndexFilterData;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportQuizResultsAction
{
    public function toExcel(QuizResultsIndexFilterData $filters, ?array $restrictToCourseIds = null): StreamedResponse
    {
        $attempts = $this->getFilteredAttempts($filters, $restrictToCourseIds);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $isSurvey = $filters->isSurveysTab();

        if ($isSurvey) {
            $headers = [
                'Курс',
                'Модуль',
                'Урок',
                'Опитування',
                'Студент',
                'Email',
                'Завершено',
            ];
        } else {
            $headers = [
                'Курс',
                'Модуль',
                'Урок',
                'Квіз',
                'Студент',
                'Email',
                'Бали',
                'Макс. балів',
                '%',
                'Статус',
                'Спроба',
                'Дата',
            ];
        }

        foreach ($headers as $col => $header) {
            $sheet->setCellValue([$col + 1, 1], $header);
        }

        $row = 2;
        foreach ($attempts as $attempt) {
            $quiz = $attempt->quiz;
            $lesson = $quiz->quizzable;
            $module = $lesson->module;
            $course = $module->course;

            if ($isSurvey) {
                $sheet->setCellValue([1, $row], $course->name);
                $sheet->setCellValue([2, $row], $module->name);
                $sheet->setCellValue([3, $row], $lesson->name);
                $sheet->setCellValue([4, $row], $quiz->title);
                $sheet->setCellValue([5, $row], $attempt->student?->full_name ?? '');
                $sheet->setCellValue([6, $row], $attempt->student?->email ?? '');
                $sheet->setCellValue([7, $row], $attempt->completed_at?->format('d.m.Y H:i'));
            } else {
                $sheet->setCellValue([1, $row], $course->name);
                $sheet->setCellValue([2, $row], $module->name);
                $sheet->setCellValue([3, $row], $lesson->name);
                $sheet->setCellValue([4, $row], $quiz->title);
                $sheet->setCellValue([5, $row], $attempt->student?->full_name ?? '');
                $sheet->setCellValue([6, $row], $attempt->student?->email ?? '');
                $sheet->setCellValue([7, $row], $attempt->score);
                $sheet->setCellValue([8, $row], $attempt->max_score);
                $sheet->setCellValue([9, $row], $attempt->scorePercentage.'%');
                $sheet->setCellValue([10, $row], $attempt->passed ? 'Пройшов' : 'Не пройшов');
                $sheet->setCellValue([11, $row], $attempt->attempt_number);
                $sheet->setCellValue([12, $row], $attempt->completed_at?->format('d.m.Y H:i'));
            }
            $row++;
        }

        $lastCol = $isSurvey ? 'G' : 'L';
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $filename = $isSurvey ? 'survey-results' : 'quiz-results';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename.'-'.now()->format('Y-m-d').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return Collection<int, StudentQuizAttempt>
     */
    private function getFilteredAttempts(QuizResultsIndexFilterData $filters, ?array $restrictToCourseIds): Collection
    {
        $isSurvey = $filters->isSurveysTab();

        $query = StudentQuizAttempt::query()
            ->with(['student', 'quiz.quizzable.module.course'])
            ->whereHas('quiz', fn ($q) => $q->where('is_survey', $isSurvey))
            ->whereHas('quiz', fn ($q) => $q->where('quizzable_type', Lesson::class));

        if ($restrictToCourseIds !== null) {
            $query->whereHas('quiz.quizzable.module', fn ($q) => $q->whereIn('course_id', $restrictToCourseIds));
        }

        if ($filters->isQuizzesTab() && ($passed = $filters->getPassedFilter()) !== null) {
            $query->where('passed', $passed);
        }

        if ($filters->course_id) {
            $query->whereHas('quiz.quizzable.module', fn ($q) => $q->where('course_id', $filters->course_id));
        }

        if ($filters->module_id) {
            $query->whereHas('quiz.quizzable', fn ($q) => $q->where('module_id', $filters->module_id));
        }

        if ($filters->lesson_id) {
            $query->whereHas('quiz', fn ($q) => $q->where('quizzable_id', $filters->lesson_id)->where('quizzable_type', Lesson::class));
        }

        if ($filters->quiz_id) {
            $query->where('quiz_id', $filters->quiz_id);
        }

        if ($filters->search) {
            $searchTerm = $filters->search;
            $query->whereHas('student', function ($q) use ($searchTerm) {
                $q->where(function ($sq) use ($searchTerm) {
                    $sq->where('name', 'ilike', "%{$searchTerm}%")
                        ->orWhere('surname', 'ilike', "%{$searchTerm}%")
                        ->orWhere('email', 'ilike', "%{$searchTerm}%");
                });
            });
        }

        return $query
            ->latest('completed_at')
            ->get();
    }
}
