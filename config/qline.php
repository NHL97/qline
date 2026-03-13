<?php

return [
    'wa_number' => env('QLINE_WA_NUMBER'),

    'meta' => [
        'app_secret'        => env('META_APP_SECRET', ''),
        'verify_token'      => env('META_VERIFY_TOKEN', 'qline_webhook_verify'),
        'access_token'      => env('META_ACCESS_TOKEN', ''),
        'phone_number_id'   => env('META_PHONE_NUMBER_ID', ''),
        'api_version'       => env('META_API_VERSION', 'v19.0'),
    ],
];