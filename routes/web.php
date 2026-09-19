<?php
return [
    'GET /' => [DashboardController::class, 'index'],
    'GET /login' => [AuthController::class, 'form'],
    'POST /login' => [AuthController::class, 'login'],
    'POST /setup' => [AuthController::class, 'setup'],
    'POST /logout' => [AuthController::class, 'logout'],
    'GET /products' => [BusinessController::class, 'products'],
    'GET /suppliers' => [BusinessController::class, 'suppliers'],
    'GET /customers' => [BusinessController::class, 'customers'],
    'GET /inventory' => [BusinessController::class, 'inventory'],
    'GET /purchases' => [BusinessController::class, 'purchases'],
    'POST /purchases' => [BusinessController::class, 'purchase'],
    'GET /sales' => [BusinessController::class, 'sales'],
    'POST /sales' => [BusinessController::class, 'sale'],
    'GET /reports' => [BusinessController::class, 'reports'],
    'POST /master' => [BusinessController::class, 'saveMaster'],
];
