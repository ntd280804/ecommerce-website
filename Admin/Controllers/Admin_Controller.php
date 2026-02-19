<?php
    require_once '../Models/User_Model.php';
class AdminController {

    public function index() {
        // Kiểm tra quyền truy cập
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=Error404");
            exit();
        }
    
        // Tạo đối tượng User
        $usermodel = new User();
    
        // Kiểm tra và thiết lập trạng thái mặc định
        $status = $_GET['status'] ?? 'all';
    
        // Chuyển hướng nếu không có tham số status
        if (empty($_GET['status'])) {
            header("Location: ./index.php?controller=admin&action=index&status=all");
            exit();
        }
    
        // Lấy danh sách admin theo trạng thái
        $admins = $usermodel->getUsersByRole('Admin');
        
        // Filter by status if needed
        if ($status !== 'all') {
            $admins = array_filter($admins, function($admin) use ($status) {
                return $admin['status'] === $status;
            });
        }
    
        // Hiển thị trang danh sách admin
        include './Views/ListAdmins.php';
    } 
    public function toggleStatus() {
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status'];
            
            
            $usermodel = new User();
            
            // Call a method to update the admin's status
            if ($usermodel->updateStatus($id, $status)) {
                header("Location: ./index.php?controller=admin&action=index");
                exit();
            } else {
                echo "Error updating admin status.";
            }
        }
    }
    public function newpass(){
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        $usermodel = new User();
    
        $id = $_GET['id']; // Lấy id từ URL
        $admin = $usermodel->getById($id); // Gọi hàm lấy dữ liệu trong model
        include './Views/NewPass.php';
    }
    public function add() {
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        include './Views/AddAdmin.php';
    }
    public function store() {
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        // Create an instance of User
        $user = new User();
    
        // Get form data
        $user->name = $_POST['name'];
        $user->email = $_POST['mail'];
        $user->password = $_POST['pass'];
        $user->phone = $_POST['phone'];
        $user->address = $_POST['address'];
        $user->status = 'active'; // Default status
        $user->role = $_POST['type']; // Get type from form (Admin or Staff)
        // Check if the email already exists
        if ($user->isEmailExists($user->email)) {
            echo "Email already exists. Please use a different email.";
            return;
        }
    
        // Insert the new admin into the database
        if ($user->insert()) {
            // Redirect to the admin list on success
            header("Location: ./index.php?controller=admin&action=index");
            exit();
        } else {
            echo "Error when adding the admin.";
        }
    }
    
    public function update() {
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        $usermodel = new User();
    
        $id = $_POST['id']; // Dữ liệu từ hidden input
        $usermodel->password = $_POST['password']; // Mật khẩu mới

    
        if ($usermodel->updatePassword($id, $usermodel->password)) {
            $this->index(); // Quay lại danh sách
        } else {
            echo "Lỗi khi cập nhật mật khẩu.";
        }
    }
    public function login() {
        if (isset($_SESSION['user_id'])) {  // Giả sử 'user' là key lưu thông tin đăng nhập
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        include './Views/Login.php';
    }
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $usermodel = new User();
            $admin = $usermodel->login($email, $password);

            if ($admin) {
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_name'] = $admin['name'];
                $_SESSION['user_role'] = $admin['role'];
                header("Location: ./index.php?controller=home&action=index");
                exit();
            } else {
                $error_message = "Email hoặc mật khẩu không đúng!";
                header("Location: ./index.php?controller=admin&action=login");
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
    public function delete() {
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            
            $usermodel = new User();
            
            // Call a method to update the admin's status
            if ($usermodel->delete($id)) {
                header("Location: ./index.php?controller=admin&action=index");
                exit();
            } else {
                echo "Error delete.";
            }
        }
    }
}
