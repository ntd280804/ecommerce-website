<?php
require_once("../Config/Database.php"); 

class OrderModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function placeOrder($userId, $cartItems, $totalAmount) {
        try {
            $this->conn->beginTransaction();
    
            // Tạo đơn hàng mới
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, status) VALUES (?, 'Processing')");
            $stmt->execute([$userId]);
            $orderId = $this->conn->lastInsertId();
    
            // Thêm chi tiết đơn hàng
            $stmtDetail = $this->conn->prepare("INSERT INTO order_details (order_id, product_id, qty, price, total) VALUES (?, ?, ?, ?, ?)");
    
            foreach ($cartItems as $item) {
                $total = $item['qty'] * $item['price'];
                $stmtDetail->execute([$orderId, $item['product_id'], $item['qty'], $item['price'], $total]);
            }
    
            $this->conn->commit();
            return $orderId;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
    

}
