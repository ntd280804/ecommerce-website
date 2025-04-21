<?php
require_once("../Config/Database.php"); 

class AdminModel {
    private $conn;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $status; // Assuming you have a status field in your admins table
    public $type; // Assuming you have a status field in your admins table
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function getAllAdmins() {
        $stmt = $this->conn->prepare("SELECT * FROM admins");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateStatus($id, $currentStatus) {
        // Toggle the status based on the current status
        $newStatus = ($currentStatus == 'Active') ? 'Inactive' : 'Active';
    
        $sql = "UPDATE admins SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    public function getByStatus($status) {
        // Assuming you are using PDO to interact with the database
        if ($status === 'all') {
            $query = "SELECT * FROM admins";
        } else {
            $query = "SELECT * FROM admins WHERE status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insert() {
        $sql = "INSERT INTO admins (name, email, password, phone, address, status, type) 
                VALUES (:name, :email, :password, :phone, :address, :status, :type)";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':type', $this->type);
    
        return $stmt->execute();
    }    
    public function getById($id) {
        $sql = "SELECT * FROM admins WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id) {
        $sql = "UPDATE admins SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function delete($id) {
        $sql = "DELETE FROM admins WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function login($email, $password) {
        $sql = "SELECT * FROM admins WHERE email = :email AND status = 'Active' LIMIT 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin['password'] == $password) {
            return $admin; // Đăng nhập thành công
        }

        return false; // Đăng nhập thất bại
    }
    public function isEmailExists($email, $table = 'admins') {
        // Chỉ cho phép kiểm tra ở bảng 'admins' hoặc 'admins' để tránh SQL injection
        if (!in_array($table, ['admins', 'admins'])) {
            throw new Exception("Invalid table name.");
        }
    
        $sql = "SELECT id FROM $table WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
}
