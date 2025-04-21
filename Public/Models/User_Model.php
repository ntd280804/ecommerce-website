<?php
require_once("../Config/Database.php"); 

class UserModel {
    private $conn;
    public $name;
    public $slug;
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email AND status = 'Active' LIMIT 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['password'] == $password) {
            return $user; // Đăng nhập thành công
        }

        return false; // Đăng nhập thất bại
    }
    public function insert() {
        $sql = "INSERT INTO users (name, email, password, phone, address, status) 
                VALUES (:name, :email, :password, :phone, :address, :status)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }
    
    public function isEmailExists($email, $table = 'users') {
        // Chỉ cho phép kiểm tra ở bảng 'users' hoặc 'admins' để tránh SQL injection
        if (!in_array($table, ['users', 'admins'])) {
            throw new Exception("Invalid table name.");
        }
    
        $sql = "SELECT id FROM $table WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
    
}
