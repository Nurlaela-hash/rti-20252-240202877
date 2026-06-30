<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

final class HealthController
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'laravel',
            'timestamp' => now()->toISOString(),
        ]);
    }
}
