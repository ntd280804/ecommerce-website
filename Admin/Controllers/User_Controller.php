<?php
    require_once './Models/User_Model.php';
class UserController {

    public function index() {
        $usermodel = new UserModel();
        if (empty($_GET['status'])) {
            header("Location: ./index.php?controller=user&action=index&status=all");
            exit();
        }
    
        // Get the status, defaulting to 'all' if not set
        $status = $_GET['status'] ?? 'all'; 
        $users = $usermodel->getByStatus($status); // Fetch brands based on status
        include './Views/ListUsers.php';
    }
    public function toggleStatus() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status'];
            
            
            $usermodel = new UserModel();
            
            // Call a method to update the brand's status
            if ($usermodel->updateStatus($id, $status)) {
                header("Location: ./index.php?controller=user&action=index");
                exit();
            } else {
                echo "Error updating brand status.";
            }
        }
    }
    public function newpass()
    {
        $usermodel = new UserModel();
    
        $id = $_GET['id']; // Lấy id từ URL
        $user = $usermodel->getById($id); // Gọi hàm lấy dữ liệu trong model
        include './Views/NewPass.php';
    }
    public function add() {
        include './Views/AddUser.php';
    }
    public function store() {
        // Create an instance of UserModel
        $user = new UserModel();

        // Get form data
        $user->name = $_POST['name'];
        $user->email = $_POST['mail'];
        $user->password = $_POST['pass'];
        $user->phone = $_POST['phone'];
        $user->address = $_POST['address'];
        $user->status = 'Active'; // Set default status to Active

        // Insert the new user into the database
        if ($user->insert()) {
            // Redirect to the user list
            header("Location: ./index.php?controller=user&action=index");
            exit();
        } else {
            echo "Error when adding the user.";
        }
    }
    public function update() {
        $usermodel = new userModel;
    
        $id = $_POST['id']; // Dữ liệu từ hidden input
        $usermodel->password = $_POST['password']; // Mật khẩu mới

    
        if ($usermodel->update($id)) {
            $this->index(); // Quay lại danh sách
        } else {
            echo "Lỗi khi cập nhật mật khẩu.";
        }
    }
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
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
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

    public function logout() {
        session_start(); // bắt buộc có dòng này để truy cập $_SESSION
        session_unset(); // xóa tất cả biến session
        session_destroy(); // huỷ session
        header("Location: ./index.php?controller=home&action=index");
        exit();
    }
}
