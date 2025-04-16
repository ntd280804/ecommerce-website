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
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // Đăng nhập thành công
        }

        return false; // Đăng nhập thất bại
    }
    public function isEmailExists($email) {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
}
