<?php

declare(strict_types=1);

use App\Controllers\AnalyticsController;

$controller = new AnalyticsController();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$apiRoutes = [
    'POST /api/analytics/consent' => 'consent',
    'POST /api/analytics/events' => 'events',
    'POST /api/analytics/identify' => 'identify',
    'GET /api/analytics/stats' => 'stats',
];

$routeKey = $method . ' ' . $requestUri;

if (isset($apiRoutes[$routeKey])) {
    $controller->{$apiRoutes[$routeKey]}();
    exit;
}

http_response_code(404);
header('Content-Type: application/json; charset=UTF-8');
echo json_encode(['ok' => false, 'error' => 'Not found']);
