<?php

namespace App\Domains\Shared\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

class Phone implements Stringable, JsonSerializable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (! preg_match('/^\+380\d{9}$/', $value)) {
            throw new InvalidArgumentException("Invalid phone format: {$value}. Expected format: +380XXXXXXXXX");
        }

        $this->value = $value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
