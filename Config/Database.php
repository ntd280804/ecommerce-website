<?php
class Database {
    private static $pdo = null;

    public static function connect($username = "root", $password = "root123", $host = "db", $dbname = "pokemon_store") {
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
                self::$pdo = new PDO($dsn, $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Lỗi kết nối database: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

