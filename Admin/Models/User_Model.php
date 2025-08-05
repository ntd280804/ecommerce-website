<?php
require_once("../Config/Database.php"); 

class UserModel {
    private $conn;
    public $name;
    public $email;
    public $role;
    public $password;
    public $phone;
    public $address;
    public $status; // Assuming you have a status field in your users table
    public function __construct() {
        $this->conn = Database::connect(); // Dùng PDO từ class Database
    }
    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateStatus($id, $currentStatus) {
        // Toggle the status based on the current status
        $newStatus = ($currentStatus == 'Active') ? 'Inactive' : 'Active';
    
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    public function getByStatus($status) {
        // Assuming you are using PDO to interact with the database
        if ($status === 'all') {
            $query = "SELECT * FROM users";
        } else {
            $query = "SELECT * FROM users WHERE status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($status !== 'all') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insert() {
        $sql = "INSERT INTO users (name, email, password, phone, address, status, role) 
        VALUES (:name, :email, :password, :phone, :address, :status, :role)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }
    public function updateRole($id, $role) {
    $sql = "UPDATE users SET role = :role WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id) {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function login($email, $password) {
        $sql = "SELECT * FROM admins WHERE email = :email AND status = 'Active' LIMIT 1 ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['password'] == $password) {
            return $user; // Đăng nhập thành công
        }

        return false; // Đăng nhập thất bại
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
    public function getUserNameById($userId) {
        $stmt = $this->conn->prepare("
            SELECT name 
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
    
}
