<?php

namespace App\Domains\Payment\Contracts;

use App\Domains\Payment\Data\PaymentCallbackData;
use App\Domains\Payment\Data\PaymentRequestData;
use App\Domains\Transaction\Models\Transaction;

interface PaymentGatewayInterface
{
    public function preparePayment(Transaction $transaction): PaymentRequestData;

    public function verifyCallback(array $data): bool;

    public function parseCallback(array $data): PaymentCallbackData;

    public function generateCallbackResponse(PaymentCallbackData $callback): array;
}
