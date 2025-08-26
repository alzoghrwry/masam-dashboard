<?php
namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;

final class App
{
    private static ?PDO $pdo = null;

    public static function init(): void
    {
        if (self::$pdo === null) {
          
        
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); 
            $dotenv->load();

            try {
                $host = $_ENV['DB_HOST'];
                $db   = $_ENV['DB_NAME'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASS'];
                $charset = $_ENV['DB_CHARSET'];

                $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

                self::$pdo = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }

    public static function db(): PDO
    {
        if (self::$pdo === null) {
            self::init();
        }
        return self::$pdo;
    }
}
