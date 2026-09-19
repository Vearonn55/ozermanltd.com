<?php

declare(strict_types=1);

/**
 * Default Admin module configuration.
 * Host may override via config/admin.php (merged in bootstrap).
 */
return [
    'brand_name' => 'CMS',
    'view_site_url' => '/en',
    'mount_path' => '/admin',
    'session_name' => null, // use PHP default unless set
    'upload_path' => null,  // set by host; default public/uploads
    'upload_url' => '/uploads',
    'max_upload_bytes' => 1024 * 1024 * 1024, // 1 GB
    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'application/pdf',
    ],
    'login_rate_limit' => 5,
    'login_rate_window_seconds' => 900,
    'roles' => [
        'editor' => 1,
        'content_manager' => 2,
        'super_admin' => 3,
    ],
    'features' => [
        'pages' => true,
        'news' => true,
        'projects' => true,
        'sectors' => true,
        'hero_slides' => true,
        'banners' => true,
        'stats' => true,
        'site_sections' => true,
        'gallery' => true,
        'stores' => true,
        'partnerships' => true,
        'team' => true,
        'offices' => true,
        'menus' => true,
        'categories' => true,
        'comments' => true,
        'contacts' => true,
        'analytics' => true,
        'media' => true,
        'seo' => true,
        'settings' => true,
        'users' => true,
        'activity' => true,
        'logs' => true,
    ],
    'default_login_hint' => '',
    'allow_page_php' => true,
    // Public URLs without locale prefix (served by QrLandingController).
    'locale_free_paths' => ['qr', 'catalogues'],
];
