<?php
require_once './Models/User_Model.php';

class UserController {
    public function login() {
        include './Views/Login.php';
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $usermodel = new UserModel();
            $user = $usermodel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: ./index.php?controller=home&action=index");
                exit();
            } else {
                $error_message = "Email hoặc mật khẩu không đúng!";
                header("Location: ./index.php?controller=user&action=login");
            }
        } else {
            include './Views/Login.php';
            exit();
        }
    }
    public function register() {
        include './Views/Register.php';
    }
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
    
            // Validate basic inputs
            if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
                $error_message = "Vui lòng điền đầy đủ thông tin bắt buộc.";
                include './Views/Register.php';
                return;
            }
    
            if ($password !== $confirmPassword) {
                $error_message = "Mật khẩu không khớp.";
                include './Views/Register.php';
                return;
            }
    
            $usermodel = new UserModel();
    
            if ($usermodel->isEmailExists($email)) {
                $error_message = "Email đã tồn tại.";
                include './Views/Register.php';
                return;
            }
    
            // Đăng ký người dùng
            $result = $usermodel->register($name, $email, $password, $phone, $address);
    
            if ($result) {
                header("Location: ./index.php?controller=user&action=login");
                exit();
            } else {
                $error_message = "Có lỗi xảy ra khi tạo tài khoản.";
                include './Views/Register.php';
                return;
            }
        } else {
            header("Location: ./index.php?controller=user&action=register");
            exit();
        }
    }
    
    public function forgotpassword() {
        include './Views/Forgot-password.php';
    }
    public function logout() {
        session_start(); // bắt buộc có dòng này để truy cập $_SESSION
        session_unset(); // xóa tất cả biến session
        session_destroy(); // huỷ session
        header("Location: ./index.php?controller=home&action=index");
        exit();
    }
}
