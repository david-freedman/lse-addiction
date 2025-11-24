<?php

namespace App\Domains\Transaction\ViewModels;

use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Enums\TransactionStatus;

readonly class TransactionStatsViewModel
{
    private int $totalCount;

    private int $completedCount;

    private int $processingCount;

    private float $totalAmount;

    public function __construct(Student $student)
    {
        $allTransactions = $student->transactions()->get();

        $this->totalCount = $allTransactions->count();
        $this->completedCount = $allTransactions->where('status', TransactionStatus::Completed)->count();
        $this->processingCount = $allTransactions->where('status', TransactionStatus::Processing)->count();
        $this->totalAmount = $allTransactions
            ->where('status', TransactionStatus::Completed)
            ->sum('amount');
    }

    public function totalCount(): int
    {
        return $this->totalCount;
    }

    public function completedCount(): int
    {
        return $this->completedCount;
    }

    public function processingCount(): int
    {
        return $this->processingCount;
    }

    public function totalAmount(): float
    {
        return $this->totalAmount;
    }

    public function formattedTotalAmount(): string
    {
        return number_format($this->totalAmount, 0, ',', ' ').' ₴';
    }
}
