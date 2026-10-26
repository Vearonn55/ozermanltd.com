<?php

declare(strict_types=1);

return [
    'name' => 'Özerman Ticaret',
    // V 1.0
    'version' => 'V 1.0',
    'tagline' => 'Distinguished brands. A strong local structure.',
    'url' => rtrim(env('APP_URL', 'https://ozermanltd.com'), '/'),
    'env' => env('APP_ENV', 'development'),
    'default_locale' => 'en',
    'locales' => [
        'en' => ['name' => 'English', 'dir' => 'ltr'],
        'tr' => ['name' => 'Türkçe', 'dir' => 'ltr'],
        'ar' => ['name' => 'العربية', 'dir' => 'rtl'],
    ],
    'contact' => [
        'email' => 'info@ozermanltd.com',
        'phone' => '',
        'address' => 'Kıbrıs',
    ],
    'social' => [],
    'under_construction' => env_bool('SITE_UNDER_CONSTRUCTION', false),
    'cloudflare' => [
        'enforce' => env_bool('CLOUDFLARE_ENFORCE', false),
    ],
];
