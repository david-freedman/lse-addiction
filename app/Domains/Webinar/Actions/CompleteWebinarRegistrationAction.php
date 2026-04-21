<?php

namespace App\Domains\Webinar\Actions;

use App\Domains\Transaction\Models\Transaction;
use App\Domains\Webinar\Models\Webinar;

class CompleteWebinarRegistrationAction
{
    public static function execute(Transaction $transaction): void
    {
        $webinar = $transaction->purchasable;

        if (!$webinar instanceof Webinar) {
            throw new \InvalidArgumentException('Transaction is not for a webinar');
        }

        $student = $transaction->student;

        if ($webinar->isRegistered($student)) {
            $webinar->students()->updateExistingPivot($student->id, [
                'transaction_id' => $transaction->id,
            ]);
            return;
        }

        $webinar->students()->attach($student->id, [
            'registered_at'  => now(),
            'transaction_id' => $transaction->id,
        ]);
    }
}
