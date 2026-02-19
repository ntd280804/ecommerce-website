<?php
require_once '../Models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    // Unified login method - handles both admin and user login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $redirect = $_POST['redirect'] ?? 'public'; // 'admin' or 'public'
            
            $user = $this->userModel->login($email, $password);
            
            if ($user) {
                // Set session based on user role
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                if ($user['role'] === 'admin') {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_name'] = $user['name'];
                    $_SESSION['admin_type'] = 'Admin';
                    header("Location: ../Admin/index.php?controller=home&action=index");
                } else {
                    header("Location: index.php?controller=home&action=index");
                }
                exit();
            } else {
                $error = "Invalid email or password";
                if ($redirect === 'admin') {
                    include '../Admin/Views/Login.php';
                } else {
                    include '../Public/Views/Login.php';
                }
            }
        } else {
            // Display login form
            $redirect = $_GET['redirect'] ?? 'public';
            if ($redirect === 'admin') {
                include '../Admin/Views/Login.php';
            } else {
                include '../Public/Views/Login.php';
            }
        }
    }
    
    public function logout() {
        session_destroy();
        $redirect = $_GET['redirect'] ?? 'public';
        if ($redirect === 'admin') {
            header("Location: ../Admin/index.php?controller=user&action=login");
        } else {
            header("Location: index.php?controller=user&action=login");
        }
        exit();
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            
            if ($this->userModel->register($name, $email, $password, $phone, $address)) {
                $success = "Registration successful! Please login.";
                include '../Public/Views/Login.php';
            } else {
                $error = "Registration failed. Email may already exist.";
                include '../Public/Views/Register.php';
            }
        } else {
            include '../Public/Views/Register.php';
        }
    }
}
