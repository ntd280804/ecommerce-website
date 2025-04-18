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
    public function register($name, $email, $password, $phone, $address) {
        try {
            $sql = "INSERT INTO users (name, email, password, phone, address, status)
                    VALUES (:name, :email, :password, :phone, :address, 'Active')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // Lưu ý: Nên mã hóa mật khẩu bằng password_hash()
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log lỗi nếu cần
            return false;
        }
    }
    
    public function isEmailExists($email) {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
}
