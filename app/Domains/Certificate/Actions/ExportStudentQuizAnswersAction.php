<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Quiz\Models\Quiz;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportStudentQuizAnswersAction
{
    public function toExcel(int $courseId): StreamedResponse
    {
        $course = Course::findOrFail($courseId);
        $quiz = $this->resolveQuiz($course);

        if ($quiz === null) {
            abort(404, 'Курс не має жодного квізу з відповідями студентів.');
        }

        $questions = $quiz->questions()->with('answers')->orderBy('order')->get();

        $attempts = StudentQuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->with('student')
            ->orderBy('completed_at')
            ->get();

        // Build answer ID → text map once to avoid per-row queries
        $answerTextMap = [];
        foreach ($questions as $question) {
            foreach ($question->answers as $answer) {
                $answerTextMap[$answer->id] = $answer->answer_text;
            }
        }

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Позначка часу',
            'Результат',
            'Відсоток правильних',
            "Ім'я та прізвище",
        ];

        foreach ($questions as $question) {
            $headers[] = $question->question_text;
        }

        foreach ($headers as $col => $header) {
            $sheet->setCellValue([$col + 1, 1], $header);
        }

        $row = 2;
        foreach ($attempts as $attempt) {
            $sheet->setCellValue([1, $row], $attempt->completed_at?->format('d.m.Y') ?? '');
            $sheet->setCellValue([2, $row], "{$attempt->score} / {$attempt->max_score}");
            $sheet->setCellValue([3, $row], number_format($attempt->scorePercentage, 0).'%');
            $sheet->setCellValue([4, $row], trim(implode(' ', array_filter([
                $attempt->student?->surname,
                $attempt->student?->name,
            ]))));

            $col = 5;
            foreach ($questions as $question) {
                $questionKey = (string) $question->id;
                $selectedIds = $attempt->answers[$questionKey]['selected']
                    ?? $attempt->answers[$question->id]['selected']
                    ?? [];

                $texts = array_filter(
                    array_map(fn ($id) => $answerTextMap[$id] ?? '', (array) $selectedIds)
                );

                $sheet->setCellValue([$col, $row], implode(', ', $texts));
                $col++;
            }

            $row++;
        }

        $totalCols = 4 + $questions->count();
        for ($c = 1; $c <= $totalCols; $c++) {
            $sheet->getColumnDimensionByColumn($c)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'student-quiz-answers-course-'.$courseId.'-'.now()->format('Y-m-d').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function resolveQuiz(Course $course): ?Quiz
    {
        // First try the dedicated final quiz
        $quiz = $course->getFinalQuiz();
        if ($quiz !== null) {
            return $quiz;
        }

        // Fall back to any non-survey quiz in the course that has attempts,
        // preferring the one with the most attempts
        $lessonIds = Lesson::whereIn('module_id', $course->modules()->pluck('id'))->pluck('id');

        return Quiz::where('quizzable_type', Lesson::class)
            ->whereIn('quizzable_id', $lessonIds)
            ->where('is_survey', false)
            ->withCount('attempts')
            ->orderByDesc('attempts_count')
            ->first();
    }
}
