<?php

namespace App\Applications\Http\Student\Payment\Controllers;

use App\Domains\Payment\Actions\InitiatePaymentAction;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

final class InitiatePaymentController
{
    public function __invoke(Request $request, Transaction $transaction): View|RedirectResponse
    {
        $student = $request->user();

        if ($transaction->student_id !== $student->id) {
            abort(403, 'Unauthorized access to transaction');
        }

        if (! $transaction->isPending()) {
            return redirect()->route('student.transactions.index')
                ->with('error', 'Транзакція вже оброблена');
        }

        $provider = PaymentProvider::from(config('payment.default_provider'));

        $paymentData = InitiatePaymentAction::execute($transaction, $provider);

        $metadata = $transaction->metadata ?? [];
        $discountInfo = [
            'original_price' => $metadata['course_price'] ?? $paymentData->amount,
            'type' => $metadata['individual_discount_type'] ?? null,
            'value' => $metadata['individual_discount_value'] ?? null,
            'amount' => $metadata['individual_discount_amount'] ?? 0,
        ];

        Session::save();

        return view('student.payment.wayforpay-form', compact('paymentData', 'discountInfo'));
    }
}
