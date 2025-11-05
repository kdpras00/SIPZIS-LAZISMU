<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | Atur kebijakan CSP di sini. Gunakan array untuk setiap directive.
    |
    */

    'policy' => [

        'default-src' => [
            "'self'",
        ],

        'script-src' => [
            "'self'",
            "'unsafe-inline'",
            "'unsafe-eval'",
            "https://cdn.jsdelivr.net",
            "https://cdnjs.cloudflare.com",
            "https://fonts.bunny.net",
            "https://fonts.googleapis.com",
            "https://www.google.com",
            "https://www.gstatic.com",
            "https://app.sandbox.midtrans.com",
            "https://app.midtrans.com",
            "https://snap-assets.al-pc-id-b.cdn.gtflabs.io", // midtrans asset
        ],

        'style-src' => [
            "'self'",
            "'unsafe-inline'",
            "https://fonts.bunny.net",
            "https://fonts.googleapis.com",
            "https://cdn.jsdelivr.net",
            "https://cdnjs.cloudflare.com",
        ],

        'font-src' => [
            "'self'",
            "https://fonts.bunny.net",
            "https://fonts.gstatic.com",
            "https://cdnjs.cloudflare.com",
        ],

        'img-src' => [
            "'self'",
            "data:",
            "https://*",
        ],

        'connect-src' => [
            "'self'",
            "https://www.google.com",
            "https://www.gstatic.com",
            "https://app.sandbox.midtrans.com",
            "https://app.midtrans.com",
        ],

        'frame-src' => [
            "'self'",
            "https://www.google.com",
            "https://www.gstatic.com",
            "https://app.sandbox.midtrans.com",
            "https://app.midtrans.com",
        ],
    ],
];
