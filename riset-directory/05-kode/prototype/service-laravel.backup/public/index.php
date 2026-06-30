<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    $baseDir = dirname(__DIR__) . '/app/';

    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

$routes = require dirname(__DIR__) . '/routes/api.php';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

foreach ($routes as $route) {
    if ($route['method'] !== $method) {
        continue;
    }

    if (!preg_match($route['pattern'], $path, $matches)) {
        continue;
    }

    $handler = $route['handler'];
    $class = $handler[0];
    $action = $handler[1];
    $arguments = [];

    if ($method === 'GET') {
        if (isset($matches[1])) {
            $arguments[] = (int) $matches[1];
        } else {
            $arguments[] = $_GET;
        }
    } elseif ($method === 'POST') {
        $arguments[] = json_decode((string) file_get_contents('php://input'), true) ?: [];
    } elseif ($method === 'PUT') {
        $arguments[] = (int) $matches[1];
        $arguments[] = json_decode((string) file_get_contents('php://input'), true) ?: [];
    } elseif ($method === 'DELETE') {
        $arguments[] = (int) $matches[1];
    }

    try {
        $class::$action(...$arguments);
    } catch (Throwable $throwable) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['message' => $throwable->getMessage()]);
    }

    return;
}

http_response_code(404);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['message' => 'Not Found']);
