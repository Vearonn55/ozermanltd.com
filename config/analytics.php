<?php

return [
    'policy_version' => 'V 1.0',

    'visitor_cookie' => 'ozerman_visitor_id',
    'consent_storage_key' => 'ozerman_consent',

    'retention_days' => (int) env('ANALYTICS_RETENTION_DAYS', '365'),

    'external' => [
        'enabled' => env_bool('EXTERNAL_SYNC_ENABLED', false),
        'url' => rtrim(env('EXTERNAL_API_URL', ''), '/'),
        'ingest_path' => env('EXTERNAL_API_INGEST_PATH', '/api/ingest'),
        'api_key' => env('EXTERNAL_API_KEY', ''),
        'timeout' => (int) env('EXTERNAL_API_TIMEOUT', '10'),
    ],

    'plausible' => [
        'domain' => env('PLAUSIBLE_DOMAIN', 'ozermanltd.com'),
        'require_consent' => env_bool('ANALYTICS_REQUIRE_CONSENT', true),
    ],

    'tracked_events' => [
        'page_view',
        'page_leave',
        'click',
        'scroll_depth',
        'consent_updated',
        'consent_opened',
        'form_start',
        'form_submit',
        'form_error',
        'identify',
        'locale_switch',
        'outbound_link',
    ],
];
