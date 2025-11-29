<?php

namespace App\Domains\Course\Actions;

use App\Domains\Course\Models\Course;
use App\Domains\Discount\Actions\GetActiveDiscountAction;
use App\Domains\Student\Models\Student;
use App\Domains\Transaction\Actions\CreateTransactionAction;
use App\Domains\Transaction\Data\TransactionData;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;

class PurchaseCourseAction
{
    public static function execute(Course $course, Student $student): Transaction
    {
        $discount = GetActiveDiscountAction::execute($course, $student);

        $originalPrice = (float) $course->price;
        $individualDiscountAmount = $discount?->calculateDiscountAmount($originalPrice) ?? 0;
        $finalPrice = max(0, $originalPrice - $individualDiscountAmount);

        $transaction = CreateTransactionAction::execute(TransactionData::from([
            'student_id' => $student->id,
            'purchasable_type' => Course::class,
            'purchasable_id' => $course->id,
            'amount' => $finalPrice,
            'currency' => 'UAH',
            'status' => TransactionStatus::Pending,
            'payment_method' => null,
            'payment_reference' => null,
            'metadata' => [
                'course_name' => $course->name,
                'course_price' => $originalPrice,
                'old_price' => $course->old_price,
                'discount_amount' => $course->discount_amount,
                'individual_discount_id' => $discount?->id,
                'individual_discount_type' => $discount?->type?->value,
                'individual_discount_value' => $discount?->value,
                'individual_discount_amount' => $individualDiscountAmount,
            ],
        ]));

        return $transaction;
    }
}
