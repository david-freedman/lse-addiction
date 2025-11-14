<?php

namespace App\Domains\Course\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Customer\Models\Customer;
use App\Domains\Transaction\Actions\CreateTransactionAction;
use App\Domains\Transaction\Data\TransactionData;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;

class PurchaseCourseAction
{
    public static function execute(Course $course, Customer $customer): Transaction
    {
        $transaction = CreateTransactionAction::execute(TransactionData::from([
            'customer_id' => $customer->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $course->id,
            'amount' => $course->price,
            'currency' => 'UAH',
            'status' => TransactionStatus::Pending,
            'payment_method' => null,
            'payment_reference' => null,
            'metadata' => [
                'course_name' => $course->name,
                'course_price' => $course->price,
                'old_price' => $course->old_price,
                'discount_amount' => $course->discount_amount,
            ],
        ]));

        return $transaction;
    }
}
