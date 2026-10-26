<?php

declare(strict_types=1);

return [
    'name' => 'Özerman Ticaret',
    'version' => 'V 1.0',
    'tagline' => 'Distinguished brands. A strong local structure.',
    'url' => rtrim(getenv('APP_URL') ?: 'https://ozermanltd.com', '/'),
    'env' => getenv('APP_ENV') ?: 'development',
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
    'under_construction' => filter_var(getenv('SITE_UNDER_CONSTRUCTION') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'cloudflare' => [
        'enforce' => filter_var(getenv('CLOUDFLARE_ENFORCE') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    ],
];
