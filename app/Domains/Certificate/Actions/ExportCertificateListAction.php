<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\Certificate\Data\CertificateFilterData;
use App\Domains\Certificate\Enums\CertificateStatus;
use App\Domains\Certificate\Models\Certificate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportCertificateListAction
{
    public function toExcel(CertificateFilterData $filters, ?array $restrictToCourseIds = null): StreamedResponse
    {
        $certificates = $this->getFilteredCertificates($filters, $restrictToCourseIds);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Реєстраційний номер Провайдера',
            'Реєстраційний номер заходу',
            'Номер сертифіката',
            "Прізвище, власне ім'я, по батькові (за наявності) учасника",
            'Бали БПР',
            'Дата народження',
            "Засоби зв'язку\n(електронна адреса)",
            'Освіта',
            'Місце роботи',
            'Найменування займаної посади',
            'Результати оцінювання за проходження заходу БПР учасників заходу, які отримали сертифікати',
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValue([$col + 1, 1], $header);
        }

        $row = 2;
        foreach ($certificates as $certificate) {
            $student = $certificate->student;

            $profileMap = $student->profileFieldValues
                ->keyBy(fn ($pfv) => $pfv->profileField->key);

            $rawLevel = $profileMap['education_level']->value ?? null;
            $options = $profileMap['education_level']->profileField->options ?? [];
            $educationLabel = $rawLevel ? ($options[$rawLevel] ?? $rawLevel) : '';
            $institution = $profileMap['institution']->value ?? '';
            $diplomaNumber = $profileMap['diploma_number']->value ?? '';

            $educationParts = array_filter([$educationLabel, $institution, $diplomaNumber]);

            $fullName = trim(implode(' ', array_filter([
                $student->surname,
                $student->name,
                $student->patronymic,
            ])));

            $sheet->setCellValue([1, $row], '2556');
            $sheet->setCellValue([2, $row], $certificate->course->number);
            $sheet->setCellValue([3, $row], $certificate->certificate_number);
            $sheet->setCellValue([4, $row], $fullName);
            $sheet->setCellValue([5, $row], intdiv($certificate->study_hours, 60));
            $sheet->setCellValue([6, $row], $student->birthday?->format('d.m.Y') ?? '');
            $sheet->setCellValue([7, $row], $student->email?->value ?? (string) $student->email ?? '');
            $sheet->setCellValue([8, $row], implode(', ', $educationParts));
            $sheet->setCellValue([9, $row], $profileMap['workplace']->value ?? '');
            $sheet->setCellValue([10, $row], $profileMap['position']->value ?? '');
            $sheet->setCellValue([11, $row], number_format($certificate->grade, 0).'%');

            $row++;
        }

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'perelik-vydanykh-sertyfikativ-'.now()->format('Y-m-d').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function getFilteredCertificates(CertificateFilterData $filters, ?array $restrictToCourseIds)
    {
        $query = Certificate::query()
            ->withTrashed()
            ->with(['student.profileFieldValues.profileField', 'course']);

        if ($restrictToCourseIds !== null) {
            $query->whereIn('course_id', $restrictToCourseIds);
        }

        if ($filters->search) {
            $search = $filters->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'ilike', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%")
                            ->orWhere('surname', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('course', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    });
            });
        }

        if ($filters->course_id) {
            $query->where('course_id', $filters->course_id);
        }

        if ($filters->student_id) {
            $query->where('student_id', $filters->student_id);
        }

        $status = $filters->getStatusEnum();
        if ($status !== null) {
            match ($status) {
                CertificateStatus::Pending => $query->pending(),
                CertificateStatus::Published => $query->published(),
                CertificateStatus::Revoked => $query->revoked(),
            };
        }

        return $query->orderByDesc('issued_at')->get();
    }
}
