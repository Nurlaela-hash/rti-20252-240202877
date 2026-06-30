<?php

namespace App\Http\Controllers\Api;

use App\Support\Http;

final class HealthController
{
    public static function index(): void
    {
        Http::json([
            'status' => 'ok',
            'service' => 'laravel-scaffold',
            'timestamp' => gmdate('c'),
        ]);
    }
}
