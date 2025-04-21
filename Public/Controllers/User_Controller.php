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
        // Create an instance of UserModel
        $user = new UserModel();
    
        // Get form data
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];
        $user->phone = $_POST['phone'];
        $user->address = $_POST['address'];
        $user->status = 'Active'; // Default status
    
        // Check if the email already exists in 'users' table
        if ($user->isEmailExists($user->email, 'users')) {
            echo "Email already exists. Please use a different email.";
            return;
        }
    
        // Insert the new user into the database
        if ($user->insert()) {
            // Redirect to the user list on success
            header("Location: ./index.php?controller=user&action=login");
            exit();
        } else {
            echo "Error when adding the user.";
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
