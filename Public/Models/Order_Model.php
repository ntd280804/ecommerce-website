<?php
require_once("../Config/Database.php"); 

class OrderModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }

}
