<?php

declare(strict_types=1);

namespace App\Domains\Verification\Factories;

use App\Domains\Verification\Contracts\VerificationProviderInterface;
use App\Domains\Verification\Providers\AlphaSmsProvider;
use App\Domains\Verification\Providers\NullProvider;
use App\Domains\Verification\Providers\SendPulseProvider;

class VerificationProviderFactory
{
    public static function make(string $channel): VerificationProviderInterface
    {
        $providers = self::getProviders();

        foreach ($providers as $provider) {
            if ($provider->supports($channel)) {
                return $provider;
            }
        }

        return new NullProvider;
    }

    private static function getProviders(): array
    {
        $providers = [];

        if (config('services.verification.email.provider') === 'sendpulse') {
            $providers[] = new SendPulseProvider(
                eventUrl: config('services.sendpulse.event_url'),
            );
        }

        if (config('services.verification.phone.provider') === 'alphasms') {
            $providers[] = new AlphaSmsProvider(
                apiKey: config('services.alphasms.api_key'),
                baseUrl: config('services.alphasms.base_url', 'https://alphasms.ua/api/'),
            );
        }

        return $providers;
    }
}
