<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paperjet API Endpoint & Authentication
    |--------------------------------------------------------------------------
    |
    | Configure your Paperjet API key (Sanctum Bearer token) and base URL.
    | By default, points to https://api.paperjet.dev/v1 or your local dev instance.
    |
    */
    'api_key' => env('PAPERJET_API_KEY', ''),
    'endpoint' => env('PAPERJET_ENDPOINT', env('APP_URL', 'http://localhost:8000') . '/api/v1'),
    'timeout' => (int) env('PAPERJET_TIMEOUT', 15),

    /*
    |--------------------------------------------------------------------------
    | Default Print Layout Options
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'format' => 'A4',
        'orientation' => 'portrait',
        'margins' => [
            'top' => 12,
            'right' => 12,
            'bottom' => 12,
            'left' => 12,
        ],
        'sanitize_word_styles' => true,
        'normalize_typography' => true,
        'fix_table_page_breaks' => true,
        'inject_chartjs_fix' => true,
    ],
];
