<?php

return [
    'policy_version' => getenv('PRIVACY_POLICY_VERSION') ?: '1.0',

    'visitor_cookie' => 'ozerman_visitor_id',
    'consent_storage_key' => 'ozerman_consent',

    'retention_days' => (int) (getenv('ANALYTICS_RETENTION_DAYS') ?: 365),

    'external' => [
        'enabled' => filter_var(getenv('EXTERNAL_SYNC_ENABLED') ?: 'false', FILTER_VALIDATE_BOOLEAN),
        'url' => rtrim(getenv('EXTERNAL_API_URL') ?: '', '/'),
        'ingest_path' => getenv('EXTERNAL_API_INGEST_PATH') ?: '/api/ingest',
        'api_key' => getenv('EXTERNAL_API_KEY') ?: '',
        'timeout' => (int) (getenv('EXTERNAL_API_TIMEOUT') ?: 10),
    ],

    'plausible' => [
        'domain' => getenv('PLAUSIBLE_DOMAIN') ?: 'ozermanltd.com',
        'require_consent' => filter_var(getenv('ANALYTICS_REQUIRE_CONSENT') ?: 'true', FILTER_VALIDATE_BOOLEAN),
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
