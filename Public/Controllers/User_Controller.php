<?php
class UserController {
    public function login() {
        include './Views/Login.php';
    }
    public function handleLogin() {
        require_once("../Config/Database.php"); 
        require_once("./Models/User_Model.php");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->login($email, $password);

            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                include './Views/HomePage.php'; // Load lại login page kèm thông báo lỗi
                exit();
            } else {
                $error_message = "Email hoặc mật khẩu không đúng!";
                include './Views/Login.php'; // Load lại login page kèm thông báo lỗi
            }
        } else {
            include './Views/Login.php'; // Load lại login page kèm thông báo lỗi
            exit();
        }
    }

    public function register() {
        include './Views/Register.php';
    }
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        include './Views/Login.php'; // Load lại login page kèm thông báo lỗi
        exit();
    }
}
