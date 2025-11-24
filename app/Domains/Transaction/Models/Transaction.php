<?php

namespace App\Domains\Transaction\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Enums\PaymentMethod;
use App\Domains\Transaction\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'student_id',
        'purchasable_type',
        'purchasable_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_provider',
        'gateway_order_reference',
        'gateway_transaction_id',
        'gateway_response',
        'payment_reference',
        'metadata',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => TransactionStatus::class,
        'payment_method' => PaymentMethod::class,
        'payment_provider' => PaymentProvider::class,
        'metadata' => 'array',
        'gateway_response' => 'array',
        'completed_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'transaction_number';
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'purchasable_id');
    }

    public function isPending(): bool
    {
        return $this->status === TransactionStatus::Pending;
    }

    public function isProcessing(): bool
    {
        return $this->status === TransactionStatus::Processing;
    }

    public function isCompleted(): bool
    {
        return $this->status === TransactionStatus::Completed;
    }

    public function isFailed(): bool
    {
        return $this->status === TransactionStatus::Failed;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', TransactionStatus::Completed->value);
    }

    public function scopeForStudent($query, Student $student)
    {
        return $query->where('student_id', $student->id);
    }

    public function scopeByStatus($query, ?TransactionStatus $status)
    {
        if ($status === null) {
            return $query;
        }

        return $query->where('status', $status->value);
    }

    public function scopeByDateRange($query, ?string $dateFrom, ?string $dateTo)
    {
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query;
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ').' '.$this->currency;
    }

    public function getMaskedPaymentReferenceAttribute(): ?string
    {
        if (! $this->payment_reference) {
            return null;
        }

        return '•••• '.substr($this->payment_reference, -4);
    }
}
