<?php

declare(strict_types=1);

/**
 * Host overrides for the portable Admin module.
 */
return [
    'brand_name' => 'Ozerman CMS',
    'view_site_url' => '/en',
    // null → resolved to PUBLIC_PATH/uploads (public/ locally, public_html/ on cPanel)
    'upload_path' => null,
    'upload_url' => '/uploads',
    'default_login_hint' => 'admin@ozermanltd.com',
    'locale_free_paths' => ['qr', 'catalogues'],
    'max_upload_bytes' => 1024 * 1024 * 1024, // 1 GB (also raise PHP limits in cPanel)
];
