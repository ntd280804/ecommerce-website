<?php
require_once __DIR__ . '/../Models/User_Model.php';

class UserController {
    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            $userModel = new UserModel();
            $user = $userModel->getUserById($_SESSION['user_id']);
            include './Views/Profile.php';
        } else {
            header("Location: .//login.html");
        }
    }
    public function editprofile()
    {
        if (isset($_SESSION['user_id'])) {
            $userModel = new UserModel();
            $user = $userModel->getUserById($_SESSION['user_id']);
            include './Views/EditProfile.php';
        } else {
            header("Location: .//login.html");
        }
        
    }
    public function login() {
        include './Views/Login.php';
    }
    public function handleupdateprofile()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: .//login.html");
            exit;
        }
    
        $userModel = new UserModel();
        $id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = !empty($_POST['password']) ? $_POST['password'] : null;
        $phone = $_POST['phone'];
        $address = $_POST['address'];
    
        // Call update function
        $result = $userModel->updateUser($id, $name, $email, $password, $phone, $address);
    
        if ($result) {
            header("Location: ./home.html");
        } else {
            echo "Update failed.";
        }
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
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];

            // Get cart and save to SESSION
            require_once __DIR__ . '/../Models/Cart_Model.php';
            $cartModel = new CartModel();
            $totalAmount = $cartModel->getCartTotal($user['id'], $user['role']);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($user['id'], $user['role']);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;

            header("Location: ./home.html");
            exit();
        } else {
            header("Location: .//login.html");
        }
    } else {
        include './Views/Login.php';
        exit();
    }
}

    public function handleForgot_password() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordAgain = $_POST['passwordagain'] ?? '';
    
            // Kiểm tra mật khẩu trùng nhau
            if ($password !== $passwordAgain) {
                // Bạn có thể lưu lỗi vào session hoặc biến để hiển thị trong view
                include './Views/Forgot-password.php';
                return;
            }
    
            $usermodel = new UserModel();
    
            // Kiểm tra email có tồn tại và active không
            if (!$usermodel->isEmailExists($email)) {
                $error_message = "Email không tồn tại hoặc không hợp lệ!";
                include './Views/Forgot-password.php';
                return;
            }
    
    
            // Cập nhật mật khẩu mới cho user
            if ($usermodel->updatePasswordByEmail($email, $password)) {
                // Redirect về login với thông báo thành công hoặc hiển thị message
                header("Location: .//login.html");
                exit();
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật mật khẩu.";
                include './Views/Forgot-password.php';
            }
        } else {
            // Nếu không phải POST, chỉ include form nhập lại mật khẩu
            include './Views/Forgot-password.php';
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
        $user->full_name = $_POST['name']; // Map name to full_name for database
    
        // Check if the email already exists in 'users' table
        if ($user->isEmailExists($user->email)) {
            echo "Email already exists. Please use a different email.";
            return;
        }
    
        // Insert the new user into the database
        if ($user->insert()) {
            // Redirect to the user list on success
            header("Location: .//login.html");
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
        header("Location: ./home.html");
        exit();
    }
}
