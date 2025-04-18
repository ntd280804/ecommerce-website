<?php
require_once("../Config/Database.php"); 

class OrderModel {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function getByStatus($status) {
        // Assuming you are using PDO to interact with the database
        if ($status === 'all') {
            $query = "SELECT * FROM orders";
        } else {
            $query = "SELECT * FROM orders WHERE status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
