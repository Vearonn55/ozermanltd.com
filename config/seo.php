<?php

return [
    'default_robots' => 'index, follow',
    'title_suffix' => ' | Ozerman Ltd',
    'default_og_image' => '/assets/images/og-default.jpg',

    'analytics' => [
        'plausible_domain' => getenv('PLAUSIBLE_DOMAIN') ?: 'ozermanltd.com',
        'require_consent' => filter_var(getenv('ANALYTICS_REQUIRE_CONSENT') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    ],

    'consent' => [
        'storage_key' => 'ozerman_consent',
        'cookie_name' => 'ozerman_consent',
        'expiry_days' => 365,
    ],

    'legacy_import' => [
        'base_url' => rtrim(getenv('LEGACY_SITE_URL') ?: 'http://localhost:8080', '/'),
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
        'name' => 'Ozerman Ltd',
        'url' => 'https://ozermanltd.com',
        'logo' => 'https://ozermanltd.com/assets/images/logo.svg',
        'description' => 'A diversified international business group operating across trading, construction, real estate, manufacturing, logistics, and energy.',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '25 Canary Wharf',
            'addressLocality' => 'London',
            'postalCode' => 'E14 5AB',
            'addressCountry' => 'GB',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+44-20-7946-0958',
            'contactType' => 'customer service',
            'email' => 'info@ozermanltd.com',
        ],
    ],
];
