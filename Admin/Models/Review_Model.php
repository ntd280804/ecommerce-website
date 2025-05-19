<?php
require_once("../Config/Database.php"); 

class ReviewModel {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function getAllReviews() {
        $stmt = $this->conn->prepare("SELECT r.id, u.name AS user_name, p.name AS product_name, r.comment, r.rating, r.created_at 
                                      FROM reviews r
                                      JOIN users u ON r.user_id = u.id
                                      JOIN products p ON r.product_id = p.id
                                      ORDER BY r.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
