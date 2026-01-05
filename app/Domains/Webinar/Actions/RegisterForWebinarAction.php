<?php

namespace App\Domains\Webinar\Actions;

use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Actions\CreateTransactionAction;
use App\Domains\Transaction\Data\TransactionData;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Webinar\Models\Webinar;

class RegisterForWebinarAction
{
    public static function execute(Webinar $webinar, Student $student): Transaction
    {
        $price = (float) $webinar->price;

        return CreateTransactionAction::execute(TransactionData::from([
            'student_id' => $student->id,
            'purchasable_type' => Webinar::class,
            'purchasable_id' => $webinar->id,
            'amount' => $price,
            'currency' => 'UAH',
            'status' => TransactionStatus::Pending,
            'payment_method' => null,
            'payment_reference' => null,
            'metadata' => [
                'webinar_title' => $webinar->title,
                'webinar_price' => $price,
                'webinar_starts_at' => $webinar->starts_at->toIso8601String(),
                'teacher_name' => $webinar->teacher->full_name,
            ],
        ]));
    }
}
