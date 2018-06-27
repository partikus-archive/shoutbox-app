<?php declare(strict_types=1);

namespace App\Infrastructure\Pdo;

use PDO;

final class PdoFactory
{
    public static function create(): PDO
    {
        $parts = parse_url(getenv('DATABASE_URL'));
        $path = ltrim($parts['path'], "/");

        try {
            return new PDO("mysql:host={$parts['host']};port={$parts['port']};dbname={$path}", $parts['user'], $parts['pass']);
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
            exit;
        }
    }
}
