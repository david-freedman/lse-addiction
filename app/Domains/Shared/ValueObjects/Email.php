<?php

namespace App\Domains\Shared\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

class Email implements JsonSerializable, Stringable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $validated = filter_var($value, FILTER_VALIDATE_EMAIL);

        if ($validated === false) {
            throw new InvalidArgumentException("Invalid email format: {$value}");
        }

        $this->value = $validated;
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
