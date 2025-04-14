<?php
require_once("../Config/Database.php"); 

class BrandModel {
    private $conn;
    public $name;
    public $slug;
    
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    
    public function insert() {

        $sql = "INSERT INTO brands (name,slug)
                VALUES (:name,:slug)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        return $stmt->execute();
    }
    
    
    
    public function updateStatus($id, $currentStatus) {
        // Toggle the status based on the current status
        $newStatus = ($currentStatus == 'Active') ? 'Inactive' : 'Active';
    
        $sql = "UPDATE brands SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
      

    public function getByStatus($status) {
        // Assuming you are using PDO to interact with the database
        if ($status === 'all') {
            $query = "SELECT * FROM brands";
        } else {
            $query = "SELECT * FROM brands WHERE status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $sql = "SELECT * FROM brands WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id) {
        $sql = "UPDATE brands SET name = :name, slug = :slug WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
