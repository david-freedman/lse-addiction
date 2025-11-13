<?php

return [
    'default_provider' => env('PAYMENT_DEFAULT_PROVIDER', 'wayforpay'),

    'wayforpay' => [
        'merchant_account' => env('WAYFORPAY_MERCHANT_ACCOUNT', ''),
        'secret_key' => env('WAYFORPAY_SECRET_KEY', ''),
        'merchant_domain' => env('WAYFORPAY_MERCHANT_DOMAIN', env('APP_URL', 'localhost')),
        'api_url' => env('WAYFORPAY_API_URL', 'https://secure.wayforpay.com/pay'),
        'mode' => env('WAYFORPAY_MODE', 'sandbox'),
    ],
];
