<?php

namespace App\Domains\Course\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Models\Course;
use App\Domains\Customer\Models\Customer;
use App\Domains\Transaction\Actions\CompleteTransactionAction;
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
            'status' => TransactionStatus::Completed,
            'payment_method' => null,
            'payment_reference' => null,
            'metadata' => [
                'course_name' => $course->name,
                'course_price' => $course->price,
                'old_price' => $course->old_price,
                'discount_amount' => $course->discount_amount,
            ],
        ]));

        CompleteTransactionAction::execute($transaction);

        EnrollCustomerAction::execute($course, $customer);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Course,
            'subject_id' => $course->id,
            'activity_type' => ActivityType::CoursePurchased,
            'description' => 'Course purchased',
            'properties' => [
                'course_name' => $course->name,
                'customer_id' => $customer->id,
                'customer_email' => $customer->email->value,
                'amount' => $course->price,
                'transaction_number' => $transaction->transaction_number,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $transaction;
    }
}
