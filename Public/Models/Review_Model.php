<?php

class ReviewModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function createReview($userId, $productId, $orderId, $rating, $content) {
        $stmt = $this->conn->prepare("
            INSERT INTO reviews (user_id, product_id, order_id, rating, comment, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$userId, $productId, $orderId, $rating, $content]);
    }
    
    

    public function hasReviewedOrder($userId, $orderId) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) FROM reviews 
            WHERE user_id = ? AND order_id = ?
        ");
        $stmt->execute([$userId, $orderId]);
        return $stmt->fetchColumn() > 0;
    }
    public function getReviewsByOrderId($orderId) {
        $stmt = $this->conn->prepare("
            SELECT r.*, p.name AS product_name, u.name AS user_name 
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            JOIN users u ON r.user_id = u.id
            WHERE r.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
