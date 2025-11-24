<?php

return [
    'resend_interval' => env('VERIFICATION_RESEND_INTERVAL', 120),
    'hourly_limit' => env('VERIFICATION_HOURLY_LIMIT', 5),
    'max_attempts' => env('VERIFICATION_MAX_ATTEMPTS', 5),
    'backdoor_enabled' => env('VERIFICATION_BACKDOOR_ENABLED', false),
    'require_email' => env('VERIFICATION_REQUIRE_EMAIL', true),
    'require_phone' => env('VERIFICATION_REQUIRE_PHONE', true),
];
