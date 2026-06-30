<?php

use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\ProductController;

return [
    ['method' => 'GET', 'pattern' => '#^/health$#', 'handler' => [HealthController::class, 'index']],
    ['method' => 'GET', 'pattern' => '#^/api/products$#', 'handler' => [ProductController::class, 'index']],
    ['method' => 'GET', 'pattern' => '#^/api/products/(\d+)$#', 'handler' => [ProductController::class, 'show']],
    ['method' => 'POST', 'pattern' => '#^/api/products$#', 'handler' => [ProductController::class, 'store']],
    ['method' => 'PUT', 'pattern' => '#^/api/products/(\d+)$#', 'handler' => [ProductController::class, 'update']],
    ['method' => 'DELETE', 'pattern' => '#^/api/products/(\d+)$#', 'handler' => [ProductController::class, 'destroy']],
];
