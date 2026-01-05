<?php

namespace App\Applications\Http\Student\Payment\Controllers;

use App\Domains\Payment\Actions\VerifyPaymentSignatureAction;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

final class ShowPaymentReturnController
{
    public function __invoke(Request $request): View
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
            return view('student.payment.return', [
                'status' => 'error',
                'message' => 'Не вказано номер замовлення',
            ]);
        }

        $transaction = Transaction::where('transaction_number', $orderReference)->first();

        if (! $transaction) {
            return view('student.payment.return', [
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

        return view('student.payment.return', compact('transaction', 'status', 'message'));
    }
}
