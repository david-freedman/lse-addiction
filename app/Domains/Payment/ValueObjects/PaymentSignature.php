<?php

namespace App\Domains\Payment\ValueObjects;

class PaymentSignature
{
    public readonly string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function generate(array $fields, string $secretKey): self
    {
        $signatureString = implode(';', $fields);
        $hash = hash_hmac('md5', $signatureString, $secretKey);

        return new self($hash);
    }

    public static function verify(array $fields, string $secretKey, string $signature): bool
    {
        $generated = self::generate($fields, $secretKey);

        return hash_equals($generated->value, $signature);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
