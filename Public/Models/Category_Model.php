<?php
require_once(__DIR__ . '/../../Config/Database.php');

class CategoryModel {
    private $conn;
    public $name;
    public $slug;
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function getByStatus($status) {
        // Assuming you are using PDO to interact with the database
        if ($status === 'all') {
            $query = "SELECT * FROM categories";
        } else {
            $query = "SELECT * FROM categories WHERE status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll() {
        $sql = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
}
