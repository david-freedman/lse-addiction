<?php

namespace App\Domains\Transaction\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Data\TransactionFilterData;
use App\Domains\Transaction\Enums\PaymentMethod;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

readonly class TransactionListViewModel
{
    private LengthAwarePaginator $transactions;

    private Collection $students;

    private Collection $courses;

    private TransactionFilterData $filters;

    public function __construct(TransactionFilterData $filters, int $perPage = 20)
    {
        $this->filters = $filters;

        $this->students = Student::query()
            ->orderBy('surname')
            ->orderBy('name')
            ->get(['id', 'name', 'surname', 'email']);

        $this->courses = Course::orderBy('name')->get(['id', 'name']);

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

        if ($filters->amount_from !== null) {
            $query->where('amount', '>=', $filters->amount_from);
        }

        if ($filters->amount_to !== null) {
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

        $this->transactions = $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function transactions(): LengthAwarePaginator
    {
        return $this->transactions;
    }

    public function students(): Collection
    {
        return $this->students;
    }

    public function courses(): Collection
    {
        return $this->courses;
    }

    /** @return array<TransactionStatus> */
    public function statuses(): array
    {
        return TransactionStatus::cases();
    }

    /** @return array<PaymentMethod> */
    public function paymentMethods(): array
    {
        return PaymentMethod::cases();
    }

    public function filters(): TransactionFilterData
    {
        return $this->filters;
    }

    public function hasNoTransactions(): bool
    {
        return $this->transactions->isEmpty();
    }

    public function totalCount(): int
    {
        return $this->transactions->total();
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->status !== null
            || $this->filters->student_id !== null
            || $this->filters->course_id !== null
            || $this->filters->payment_method !== null
            || $this->filters->amount_from !== null
            || $this->filters->amount_to !== null
            || $this->filters->transaction_number !== null
            || $this->filters->created_from !== null
            || $this->filters->created_to !== null;
    }
}
