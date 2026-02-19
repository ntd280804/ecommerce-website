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
        $sql = "SELECT * FROM users WHERE email = :email AND status = 'active' AND role = 'user' LIMIT 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['password'] == $password) {
            return $user; // Đăng nhập thành công
        }

        return false; // Đăng nhập thất bại
    }
    public function updatePasswordByEmail($email, $hashedPassword) {
        $sql = "UPDATE users SET password = :password WHERE email = :email AND status = 'active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
    
    public function insert() {
        $sql = "INSERT INTO users (email, full_name, password, role) 
                VALUES (:email, :full_name, :password, 'user')";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':password', $this->password);

        return $stmt->execute();
    }
    
    public function isEmailExists($email, $table = 'users') {
        // Chỉ cho phép kiểm tra ở bảng 'users' để tránh SQL injection
        if ($table !== 'users') {
            throw new Exception("Invalid table name.");
        }
    
        $sql = "SELECT id FROM $table WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id AND status = 'active' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateUser($id, $name, $email, $password, $phone, $address) {
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address";
        if ($password) {
            $sql .= ", password = :password";
        }
        $sql .= " WHERE id = :id AND status = 'active'";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($password) {
            $stmt->bindParam(':password', $password);
        }
    
        return $stmt->execute();
    }
    
    
    
}
