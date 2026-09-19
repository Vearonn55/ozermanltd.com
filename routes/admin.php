<?php

declare(strict_types=1);

/**
 * Legacy path — Admin now lives in modules/Admin.
 * Kept so older docs/scripts that require routes/admin.php still work.
 */
require dirname(__DIR__) . '/modules/Admin/routes.php';
