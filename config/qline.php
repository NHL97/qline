<?php

return [
    'wa_number' => env('QLINE_WA_NUMBER'),

    'meta' => [
        'app_secret' => env('META_APP_SECRET', ''),
        'verify_token' => env('META_VERIFY_TOKEN', 'qline_webhook_verify'),
        'access_token' => env('META_ACCESS_TOKEN', ''),
        'phone_number_id' => env('META_PHONE_NUMBER_ID', ''),
        'api_version' => env('META_API_VERSION', 'v19.0'),
    ],

    'billplz' => [
        'api_key' => env('BILLPLZ_API_KEY', ''),
        'collection_id' => env('BILLPLZ_COLLECTION_ID', ''),
        'x_signature' => env('BILLPLZ_X_SIGNATURE', ''),
        'sandbox' => env('BILLPLZ_SANDBOX', true),
        'api_url' => env('BILLPLZ_SANDBOX', true)
                            ? 'https://www.billplz-sandbox.com/api/v3'
                            : 'https://www.billplz.com/api/v3',
    ],
];
