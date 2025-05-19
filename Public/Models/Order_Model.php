<?php
require_once("../Config/Database.php"); 

class OrderModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function hasReviewedOrder($userId, $orderId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ? AND order_id = ?");
        $stmt->execute([$userId, $orderId]);
        return $stmt->fetchColumn() > 0;
    }
    // Hàm tạo đơn hàng
    public function createOrder($userId, $totalAmount, $cartItems) {
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total, status, created_at, updated_at) VALUES (?, ?, 'Processing', NOW(), NOW())");
        $stmt->execute([$userId, $totalAmount]);


        // Lấy ID của đơn hàng vừa tạo
        $orderId = $this->conn->lastInsertId();

        // Thêm chi tiết đơn hàng
        foreach ($cartItems as $item) {
            $stmt = $this->conn->prepare("INSERT INTO order_details (order_id, product_id, qty, price, total, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $item['qty'],
                $item['discounted_price'],
                $item['discounted_price'] * $item['qty']
            ]);
        }

        return $orderId;
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
    public function getProductIdsByOrderId($orderId) {
        $stmt = $this->conn->prepare("
            SELECT product_id 
            FROM order_details 
            WHERE order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getOrdersByUserId($userId) {
        $stmt = $this->conn->prepare("
            SELECT 
                orders.*, 
                users.name AS user_name,
                EXISTS (
                    SELECT 1 FROM reviews WHERE user_id = :userId AND order_id = orders.id
                ) AS has_reviewed
            FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE orders.user_id = :userId
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

