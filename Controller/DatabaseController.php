<?php

class DatabaseController
{
    private const DB_HOST = 'localhost';
    private const DB_USER = 'root';
    private const DB_PASS = 'root';
    private const DB_NAME = 'new-php-blog';

    private static ?DatabaseController $instance = null;
    private ?PDO $connection = null;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance(): DatabaseController
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnect(): ?PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME,
                    self::DB_USER,
                    self::DB_PASS,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                error_log("Database Connection Error: " . $e->getMessage());
                return null;
            }
        }
        return $this->connection;
    }
}
