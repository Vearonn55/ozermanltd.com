<?php

return [
    'driver' => 'mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_DATABASE') ?: 'ozermanltd',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',
    'use_dummy_data' => filter_var(getenv('DB_USE_DUMMY_DATA') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'fallback_to_dummy' => filter_var(getenv('DB_FALLBACK_DUMMY') ?: 'true', FILTER_VALIDATE_BOOLEAN),
];
