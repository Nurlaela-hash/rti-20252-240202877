<?php

namespace App\Support;

final class Http
{
    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public static function input(): array
    {
        $raw = file_get_contents('php://input');
        if (!is_string($raw) || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    public static function integer(mixed $value, int $default = 0): int
    {
        if ($value === null || $value === '') {
            return $default;
        }

        $parsed = filter_var($value, FILTER_VALIDATE_INT);
        return $parsed === false ? $default : (int) $parsed;
    }

    public static function number(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (float) $value : null;
    }
}
