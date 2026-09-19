<?php

declare(strict_types=1);

return [
    'name' => 'Özerman Ticaret',
    'tagline' => 'Import. Partner. Deliver.',
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
        'phone' => '+90 212 000 00 00',
        'address' => 'Lorem Cad. No:1, Dummy Mah., Istanbul, Turkey',
    ],
    'social' => [
        'linkedin' => 'https://linkedin.com/company/ozermanltd',
        'twitter' => 'https://twitter.com/ozermanltd',
        'instagram' => 'https://instagram.com/ozermanltd',
        'youtube' => 'https://youtube.com/ozermanltd',
    ],
    'under_construction' => filter_var(getenv('SITE_UNDER_CONSTRUCTION') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'cloudflare' => [
        'enforce' => filter_var(getenv('CLOUDFLARE_ENFORCE') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    ],
];
