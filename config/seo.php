<?php

return [
    'default_robots' => 'index, follow',
    'title_suffix' => ' | Özerman Ticaret',
    'default_og_image' => '/assets/images/og-default.jpg',

    'analytics' => [
        'plausible_domain' => env('PLAUSIBLE_DOMAIN', 'ozermanltd.com'),
        'require_consent' => env_bool('ANALYTICS_REQUIRE_CONSENT', true),
    ],

    'consent' => [
        'storage_key' => 'ozerman_consent',
        'cookie_name' => 'ozerman_consent',
        'expiry_days' => 365,
    ],

    'legacy_import' => [
        'base_url' => rtrim(env('LEGACY_SITE_URL', 'http://localhost:8080'), '/'),
        'locales' => ['en', 'tr', 'ar'],
        'paths' => [
            '',
            'about-us',
            'sectors',
            'projects',
            'news',
            'gallery',
            'contact',
        ],
    ],

    'organization_schema' => [
        '@type' => 'Organization',
        'name' => 'Özerman Ticaret',
        'url' => 'https://ozermanltd.com',
        'logo' => 'https://ozermanltd.com/assets/images/brand/logo-horizontal-dark.png',
        'description' => 'Özerman Ticaret is a distribution and trading company in Cyprus that connects brands with quality retail service.',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Kıbrıs',
            'addressCountry' => 'CY',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'email' => 'info@ozermanltd.com',
            'areaServed' => 'Cyprus',
            'availableLanguage' => ['Turkish', 'English', 'Arabic'],
        ],
    ],
];
