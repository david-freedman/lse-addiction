<?php

namespace App\Applications\Http\Student\Catalog\Controllers;

use App\Domains\Course\Actions\CompleteCoursePurchaseAction;
use App\Domains\Course\Actions\PurchaseCourseAction;
use App\Domains\Course\Models\Course;
use App\Domains\Transaction\Actions\CompleteTransactionAction;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Http\RedirectResponse;

final class PurchaseCourseController
{
    public function __invoke(Course $course): RedirectResponse
    {
        $student = auth()->user();

        if ($course->hasStudent($student)) {
            return redirect()
                ->back()
                ->with('error', 'Ви вже придбали цей курс');
        }

        if (! $course->isPublished()) {
            return redirect()
                ->back()
                ->with('error', 'Цей курс недоступний для покупки');
        }

        $existingTransaction = Transaction::where('student_id', $student->id)
            ->where('purchasable_type', Course::class)
            ->where('purchasable_id', $course->id)
            ->where('status', TransactionStatus::Pending)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->first();

        if ($existingTransaction) {
            return redirect()
                ->route('student.payment.initiate', $existingTransaction)
                ->with('info', 'У вас вже є незавершена оплата для цього курсу');
        }

        if ($course->price == 0) {
            $transaction = PurchaseCourseAction::execute($course, $student);
            CompleteTransactionAction::execute($transaction);
            CompleteCoursePurchaseAction::execute($transaction);

            return redirect()
                ->route('student.courses.show', $course)
                ->with('success', 'Курс успішно придбано! Приємного навчання!');
        }

        $transaction = PurchaseCourseAction::execute($course, $student);

        return redirect()
            ->route('student.payment.initiate', $transaction)
            ->with('info', 'Будь ласка, завершіть оплату для отримання доступу до курсу');
    }
}
