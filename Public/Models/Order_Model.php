<?php
require_once("../Config/Database.php"); 

class OrderModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
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
                $item['price'],
                $item['price'] * $item['qty']
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
    public function getOrdersByUserId($userId) {
        $stmt = $this->conn->prepare("
            SELECT orders.*, users.name AS user_name
            FROM orders
            JOIN users ON orders.user_id = users.id
            WHERE orders.user_id = :userId
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT); // Truyền vào userId thay vì orderId
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy tất cả đơn hàng của người dùng
    }
}

