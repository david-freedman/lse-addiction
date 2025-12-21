<?php

namespace App\Applications\Http\Student\Webinar\Controllers;

use App\Domains\Transaction\Actions\CompleteTransactionAction;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Webinar\Actions\CompleteWebinarRegistrationAction;
use App\Domains\Webinar\Actions\RegisterForWebinarAction;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;

final class RegisterWebinarController
{
    public function __invoke(Webinar $webinar): RedirectResponse
    {
        if ($webinar->isDraft()) {
            abort(404);
        }

        if ($webinar->status === WebinarStatus::Cancelled) {
            return redirect()
                ->back()
                ->with('error', __('messages.webinar.cancelled'));
        }

        $student = auth()->user();

        if ($webinar->isRegistered($student)) {
            return redirect()
                ->back()
                ->with('error', 'Ви вже зареєстровані на цей вебінар');
        }

        if (!$webinar->hasCapacity()) {
            return redirect()
                ->back()
                ->with('error', 'На жаль, всі місця на цей вебінар зайняті');
        }

        $existingTransaction = Transaction::where('student_id', $student->id)
            ->where('purchasable_type', Webinar::class)
            ->where('purchasable_id', $webinar->id)
            ->where('status', TransactionStatus::Pending)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->first();

        if ($existingTransaction) {
            return redirect()
                ->route('student.payment.initiate', $existingTransaction)
                ->with('info', 'У вас вже є незавершена оплата для цього вебінару');
        }

        if ($webinar->is_free) {
            $transaction = RegisterForWebinarAction::execute($webinar, $student);
            CompleteTransactionAction::execute($transaction);
            CompleteWebinarRegistrationAction::execute($transaction);

            return redirect()
                ->route('student.webinar.show', $webinar)
                ->with('success', 'Ви успішно зареєструвались на вебінар!');
        }

        $transaction = RegisterForWebinarAction::execute($webinar, $student);

        return redirect()
            ->route('student.payment.initiate', $transaction)
            ->with('info', 'Будь ласка, завершіть оплату для реєстрації на вебінар');
    }
}
