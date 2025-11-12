<?php

namespace App\Domains\Transaction\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Transaction\Data\TransactionData;
use App\Domains\Transaction\Models\Transaction;

class CreateTransactionAction
{
    public static function execute(TransactionData $data): Transaction
    {
        $transaction = Transaction::create([
            'transaction_number' => self::generateTransactionNumber(),
            'customer_id' => $data->customer_id,
            'purchasable_type' => $data->purchasable_type,
            'purchasable_id' => $data->purchasable_id,
            'amount' => $data->amount,
            'currency' => $data->currency,
            'status' => $data->status,
            'payment_method' => $data->payment_method,
            'payment_reference' => $data->payment_reference,
            'metadata' => $data->metadata,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Transaction,
            'subject_id' => $transaction->id,
            'activity_type' => ActivityType::TransactionCreated,
            'description' => 'Transaction created',
            'properties' => [
                'transaction_number' => $transaction->transaction_number,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'customer_id' => $transaction->customer_id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $transaction;
    }

    private static function generateTransactionNumber(): string
    {
        $lastTransaction = Transaction::orderBy('id', 'desc')->first();
        $nextId = $lastTransaction ? $lastTransaction->id + 1 : 1;

        return 'TXN-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }
}
