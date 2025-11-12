<?php

namespace App\Domains\Transaction\ViewModels;

use App\Domains\Customer\Models\Customer;
use App\Domains\Transaction\Enums\TransactionStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class TransactionHistoryViewModel
{
    private LengthAwarePaginator $transactions;
    private ?TransactionStatus $currentStatus;
    private ?string $dateFrom;
    private ?string $dateTo;

    public function __construct(
        Customer $customer,
        ?TransactionStatus $status = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        int $perPage = 10
    ) {
        $this->currentStatus = $status;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;

        $query = $customer->transactions()
            ->with('purchasable')
            ->byStatus($status)
            ->byDateRange($dateFrom, $dateTo)
            ->orderBy('created_at', 'desc');

        $this->transactions = $query->paginate($perPage);
    }

    public function transactions(): LengthAwarePaginator
    {
        return $this->transactions;
    }

    public function hasNoTransactions(): bool
    {
        return $this->transactions->isEmpty();
    }

    public function currentStatus(): ?TransactionStatus
    {
        return $this->currentStatus;
    }

    public function dateFrom(): ?string
    {
        return $this->dateFrom;
    }

    public function dateTo(): ?string
    {
        return $this->dateTo;
    }

    public function isFilteredBy(TransactionStatus $status): bool
    {
        return $this->currentStatus === $status;
    }
}
