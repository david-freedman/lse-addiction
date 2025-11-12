<?php

namespace App\Domains\Transaction\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;

class FailTransactionAction
{
    public static function execute(Transaction $transaction, string $reason): void
    {
        if ($transaction->isFailed()) {
            return;
        }

        $metadata = $transaction->metadata ?? [];
        $metadata['failure_reason'] = $reason;

        $transaction->update([
            'status' => TransactionStatus::Failed,
            'metadata' => $metadata,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Transaction,
            'subject_id' => $transaction->id,
            'activity_type' => ActivityType::TransactionFailed,
            'description' => 'Transaction failed',
            'properties' => [
                'transaction_number' => $transaction->transaction_number,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'customer_id' => $transaction->customer_id,
                'failure_reason' => $reason,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }
}
