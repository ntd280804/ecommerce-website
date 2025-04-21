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
    public function getOrderById($id) {
        $stmt = $this->conn->prepare("
            SELECT orders.*, users.name AS user_name
            FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE orders.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function getOrderDetail($id) {
        $stmt = $this->conn->prepare("SELECT od.*, p.name as product_name 
                                      FROM order_details od 
                                      JOIN products p ON od.product_id = p.id 
                                      WHERE od.order_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateStatus($orderId, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
