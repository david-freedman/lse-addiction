<?php

return [
    'resend_interval' => env('VERIFICATION_RESEND_INTERVAL', 120),
    'hourly_limit' => env('VERIFICATION_HOURLY_LIMIT', 5),
];
