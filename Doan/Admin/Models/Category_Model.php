<?php
require_once("../Config/Database.php"); 

class CategoryModel {
    private $conn;
    public $name;
    public $slug;
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function insert() {

        $sql = "INSERT INTO categories (name,slug)
                VALUES (:name,:slug)";
        
        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        return $stmt->execute();
    }

    public function delete($id) {
        if ($id == 1) {
            // Không cho phép xóa thương hiệu mặc định
            return false;
        }
    
        try {
            $this->conn->beginTransaction();
    
            // Cập nhật sản phẩm sang danh mục mặc định
            $query1 = "UPDATE products SET category_id = 1 WHERE category_id = :id";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute(['id' => $id]);
    
            // Xóa thương hiệu (nếu không phải id = 1)
            $query2 = "DELETE FROM categories WHERE id = :id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute(['id' => $id]);
    
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
