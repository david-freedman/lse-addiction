<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Payment\Actions\HandlePaymentCallbackAction;
use App\Domains\Payment\Actions\InitiatePaymentAction;
use App\Domains\Payment\Actions\VerifyPaymentSignatureAction;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Transaction\Actions\CancelTransactionAction;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PaymentController
{
    public function initiate(Request $request, Transaction $transaction): View|RedirectResponse
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

        Session::save();

        return view('customer.payment.wayforpay-form', compact('paymentData'));
    }

    public function callback(Request $request): JsonResponse
    {
        try {
            Log::info('WayForPay callback received', [
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'all_data' => $request->all(),
                'json_data' => $request->json()->all(),
                'input_data' => $request->input(),
            ]);

            $provider = PaymentProvider::from(config('payment.default_provider'));

            $callbackData = $request->json()->all();

            $response = HandlePaymentCallbackAction::execute(
                $callbackData,
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
        $orderReference = $request->input('orderReference') ?? $request->query('orderReference');

        if ($request->has('merchantSignature') && $orderReference) {
            try {
                $provider = PaymentProvider::from(config('payment.default_provider'));
                $isValid = VerifyPaymentSignatureAction::execute(
                    $request->all(),
                    $provider
                );

                if (! $isValid) {
                    Log::warning('Invalid signature on return URL', [
                        'orderReference' => $orderReference,
                        'data' => $request->all(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error verifying return signature', [
                    'error' => $e->getMessage(),
                    'orderReference' => $orderReference,
                ]);
            }
        }

        if (! $orderReference) {
            return view('customer.payment.return', [
                'status' => 'error',
                'message' => 'Не вказано номер замовлення',
            ]);
        }

        $transaction = Transaction::where('transaction_number', $orderReference)->first();

        if (! $transaction) {
            return view('customer.payment.return', [
                'status' => 'error',
                'message' => 'Транзакцію не знайдено',
            ]);
        }

        $status = $transaction->isCompleted() ? 'success' : ($transaction->isFailed() ? 'error' : 'pending');
        $message = match ($status) {
            'success' => 'Оплата успішно завершена',
            'pending' => 'Оплата обробляється',
            default => 'Виникла помилка при обробці оплати',
        };

        return view('customer.payment.return', compact('transaction', 'status', 'message'));
    }

    public function cancel(Request $request, Transaction $transaction): RedirectResponse
    {
        $customer = $request->user();

        if ($transaction->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to transaction');
        }

        CancelTransactionAction::execute($transaction);

        return redirect()->route('customer.transactions.index')
            ->with('success', 'Оплату скасовано');
    }
}
