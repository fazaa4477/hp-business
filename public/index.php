<?php
declare(strict_types=1);
session_start();

$scriptDirectory = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
define('APP_URL', $scriptDirectory === '' ? '' : $scriptDirectory);

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/functions.php';
require_once __DIR__ . '/../app/models/Dashboard.php';
require_once __DIR__ . '/../app/models/Business.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/BusinessController.php';

$routes = require __DIR__ . '/../routes/web.php';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$basePath = APP_URL;
$routePath = '/' . ltrim(substr($requestPath, strlen($basePath)), '/');
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($requestMethod === 'HEAD') {
    $requestMethod = 'GET';
}
$routeKey = $requestMethod . ' ' . $routePath;

if (!isset($routes[$routeKey])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}

[$controllerClass, $method] = $routes[$routeKey];
$controller = new $controllerClass($pdo);
$controller->$method();
