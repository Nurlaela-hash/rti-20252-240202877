<?php

namespace App\Support;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = (string) Env::get('DB_HOST', '127.0.0.1');
        $port = (int) Env::get('DB_PORT', 3306);
        $database = (string) Env::get('DB_NAME', 'benchmark_rti');
        $user = (string) Env::get('DB_USER', 'root');
        $password = (string) Env::get('DB_PASSWORD', 'root');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $database);
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        self::$connection = $pdo;

        return $pdo;
    }
}
