<?php

namespace App\Applications\Http\Student\Payment\Controllers;

use App\Domains\Payment\Actions\HandlePaymentCallbackAction;
use App\Domains\Payment\Enums\PaymentProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class ProcessPaymentCallbackController
{
    public function __invoke(Request $request): JsonResponse
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
}
