<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Payment\Actions\HandlePaymentCallbackAction;
use App\Domains\Payment\Actions\InitiatePaymentAction;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController
{
    public function initiate(Request $request, Transaction $transaction): View
    {
        $customer = $request->user();

        if ($transaction->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to transaction');
        }

        if (! $transaction->isPending()) {
            return redirect()->route('customer.transactions.index')
                ->with('error', 'Транзакція вже оброблена');
        }

        $provider = PaymentProvider::from(config('payment.default_provider'));

        $paymentData = InitiatePaymentAction::execute($transaction, $provider);

        return view('customer.payment.wayforpay-form', compact('paymentData'));
    }

    public function callback(Request $request): JsonResponse
    {
        try {
            $provider = PaymentProvider::from(config('payment.default_provider'));

            $response = HandlePaymentCallbackAction::execute(
                $request->all(),
                $provider
            );

            return response()->json($response);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'error' => 'Callback processing failed',
            ], 500);
        }
    }

    public function return(Request $request): View
    {
        $orderReference = $request->query('orderReference');

        $transaction = Transaction::where('transaction_number', $orderReference)->first();

        if (! $transaction) {
            return view('customer.payment.return', [
                'status' => 'error',
                'message' => 'Транзакцію не знайдено',
            ]);
        }

        $status = $transaction->isCompleted() ? 'success' : 'pending';
        $message = match ($status) {
            'success' => 'Оплата успішно завершена',
            'pending' => 'Оплата обробляється',
            default => 'Виникла помилка при обробці оплати',
        };

        return view('customer.payment.return', compact('transaction', 'status', 'message'));
    }
}
