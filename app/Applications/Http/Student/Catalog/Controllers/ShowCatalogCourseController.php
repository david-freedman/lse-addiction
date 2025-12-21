<?php

namespace App\Applications\Http\Student\Catalog\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Discount\Actions\GetActiveDiscountAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class ShowCatalogCourseController
{
    public function __invoke(Course $course): View
    {
        if ($course->isDraft() || $course->isHidden()) {
            abort(404);
        }

        $course->load(['teacher', 'author', 'tags']);

        $individualDiscount = null;
        $finalPrice = $course->price;

        if (Auth::check()) {
            $student = Auth::user();
            $individualDiscount = GetActiveDiscountAction::execute($course, $student);

            if ($individualDiscount) {
                $discountAmount = $individualDiscount->calculateDiscountAmount((float) $course->price);
                $finalPrice = max(0, (float) $course->price - $discountAmount);
            }
        }

        return view('student.catalog.show', compact('course', 'individualDiscount', 'finalPrice'));
    }
}
