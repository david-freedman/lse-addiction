<?php

namespace App\Domains\Transaction\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Transaction\Data\TransactionFilterData;
use App\Domains\Transaction\Models\Transaction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportTransactionsAction
{
    public function toCsv(TransactionFilterData $filters): StreamedResponse
    {
        $transactions = $this->getFilteredTransactions($filters);

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Номер транзакції',
                'Студент',
                'Email',
                'Телефон',
                'Курс',
                'Сума',
                'Валюта',
                'Статус',
                'Метод оплати',
                'Дата створення',
                'Дата завершення',
            ], ';');

            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->transaction_number,
                    $transaction->student ? "{$transaction->student->name} {$transaction->student->surname}" : '',
                    $transaction->student?->email?->value ?? '',
                    $transaction->student?->phone?->value ?? '',
                    $transaction->purchasable?->name ?? '',
                    $transaction->amount,
                    $transaction->currency,
                    $transaction->status->label(),
                    $transaction->payment_method?->label() ?? '',
                    $transaction->created_at->format('d.m.Y H:i:s'),
                    $transaction->completed_at?->format('d.m.Y H:i:s') ?? '',
                ], ';');
            }

            fclose($handle);
        }, 'transactions-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function toExcel(TransactionFilterData $filters): StreamedResponse
    {
        $transactions = $this->getFilteredTransactions($filters);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Номер транзакції',
            'Студент',
            'Email',
            'Телефон',
            'Курс',
            'Сума',
            'Валюта',
            'Статус',
            'Метод оплати',
            'Дата створення',
            'Дата завершення',
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValue([$col + 1, 1], $header);
        }

        $row = 2;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue([1, $row], $transaction->transaction_number);
            $sheet->setCellValue([2, $row], $transaction->student ? "{$transaction->student->name} {$transaction->student->surname}" : '');
            $sheet->setCellValue([3, $row], $transaction->student?->email?->value ?? '');
            $sheet->setCellValue([4, $row], $transaction->student?->phone?->value ?? '');
            $sheet->setCellValue([5, $row], $transaction->purchasable?->name ?? '');
            $sheet->setCellValue([6, $row], $transaction->amount);
            $sheet->setCellValue([7, $row], $transaction->currency);
            $sheet->setCellValue([8, $row], $transaction->status->label());
            $sheet->setCellValue([9, $row], $transaction->payment_method?->label() ?? '');
            $sheet->setCellValue([10, $row], $transaction->created_at->format('d.m.Y H:i:s'));
            $sheet->setCellValue([11, $row], $transaction->completed_at?->format('d.m.Y H:i:s') ?? '');
            $row++;
        }

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'transactions-'.now()->format('Y-m-d').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /** @return \Illuminate\Database\Eloquent\Collection<Transaction> */
    private function getFilteredTransactions(TransactionFilterData $filters): \Illuminate\Database\Eloquent\Collection
    {
        $query = Transaction::query()
            ->with(['student', 'purchasable']);

        if ($filters->search) {
            $query->where(function ($q) use ($filters) {
                $q->where('transaction_number', 'ilike', "%{$filters->search}%")
                    ->orWhereHas('student', function ($sq) use ($filters) {
                        $sq->where('name', 'ilike', "%{$filters->search}%")
                            ->orWhere('surname', 'ilike', "%{$filters->search}%")
                            ->orWhere('email', 'ilike', "%{$filters->search}%")
                            ->orWhere('phone', 'ilike', "%{$filters->search}%");
                    });
            });
        }

        if ($filters->status) {
            $query->where('status', $filters->status->value);
        }

        if ($filters->student_id) {
            $query->where('student_id', $filters->student_id);
        }

        if ($filters->course_id) {
            $query->where('purchasable_type', Course::class)
                ->where('purchasable_id', $filters->course_id);
        }

        if ($filters->payment_method) {
            $query->where('payment_method', $filters->payment_method->value);
        }

        if ($filters->amount_from) {
            $query->where('amount', '>=', $filters->amount_from);
        }

        if ($filters->amount_to) {
            $query->where('amount', '<=', $filters->amount_to);
        }

        if ($filters->transaction_number) {
            $query->where('transaction_number', $filters->transaction_number);
        }

        if ($filters->created_from) {
            $query->whereDate('created_at', '>=', $filters->created_from);
        }

        if ($filters->created_to) {
            $query->whereDate('created_at', '<=', $filters->created_to);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
