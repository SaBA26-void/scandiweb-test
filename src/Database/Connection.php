<?php

namespace App\Database;

use Exception;
use PDO;
use PDOException;

class Connection
{
    private static ?Connection $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $databaseName = 'scandiweb';
        $dsn = "mysql:host=$host;dbname=$databaseName;charset=utf8";
        try {
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log('Database Conncetion error' . $e->getMessage());
            throw new Exception("Database connection failed!", 0, $e);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null)
            self::$instance = new self();

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
