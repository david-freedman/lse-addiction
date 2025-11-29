<?php

namespace App\Domains\Discount\Actions;

use App\Domains\Discount\Models\StudentCourseDiscount;

class RemoveDiscountAction
{
    public static function execute(StudentCourseDiscount $discount): void
    {
        $discount->delete();
    }
}
