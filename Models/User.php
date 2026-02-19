<?php
require_once("../Config/Database.php"); 

class User {
    private $conn;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $status;
    public $role;
    
    public function __construct() {
        $this->conn = Database::connect();
    }
    
    // Login method for both users and admins
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email AND status = 'active' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    // Register new user
    public function register($name, $email, $password, $phone = '', $address = '') {
        // Check if email exists
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return false; // Email already exists
        }
        
        // Insert new user
        $sql = "INSERT INTO users (name, email, password, phone, address, role, status) 
                VALUES (:name, :email, :password, :phone, :address, 'user', 'active')";
        $stmt = $this->conn->prepare($sql);
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        
        return $stmt->execute();
    }
    
    // Get user by ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Update user profile
    public function update($id, $name, $phone, $address) {
        $sql = "UPDATE users SET name = :name, phone = :phone, address = :address WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Change password
    public function changePassword($id, $currentPassword, $newPassword) {
        // Verify current password
        $user = $this->getById($id);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return false;
        }
        
        // Update password
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Get all users (for admin)
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get users by status (for admin)
    public function getByStatus($status) {
        if ($status === 'all') {
            $sql = "SELECT * FROM users ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
        } else {
            $sql = "SELECT * FROM users WHERE status = :status ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Toggle user status (for admin)
    public function toggleStatus($id, $status) {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Delete user (for admin)
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
