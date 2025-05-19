<?php
    require_once './Models/Admin_Model.php';
class AdminController {

    public function index() {
        // Kiểm tra quyền truy cập
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=Error404");
            exit();
        }
    
        // Tạo đối tượng AdminModel
        $adminmodel = new AdminModel();
    
        // Kiểm tra và thiết lập trạng thái mặc định
        $status = $_GET['status'] ?? 'all';
    
        // Chuyển hướng nếu không có tham số status
        if (empty($_GET['status'])) {
            header("Location: ./index.php?controller=admin&action=index&status=all");
            exit();
        }
    
        // Lấy danh sách admin theo trạng thái
        $admins = $adminmodel->getByStatus($status);
    
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
            
            
            $adminmodel = new AdminModel();
            
            // Call a method to update the brand's status
            if ($adminmodel->updateStatus($id, $status)) {
                header("Location: ./index.php?controller=admin&action=index");
                exit();
            } else {
                echo "Error updating brand status.";
            }
        }
    }
    public function newpass(){
        if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != "Admin") {
            // Chuyển hướng về trang đăng nhập nếu không phải Admin
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        $adminmodel = new AdminModel();
    
        $id = $_GET['id']; // Lấy id từ URL
        $admin = $adminmodel->getById($id); // Gọi hàm lấy dữ liệu trong model
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
        // Create an instance of AdminModel
        $admin = new AdminModel();
    
        // Get form data
        $admin->name = $_POST['name'];
        $admin->email = $_POST['mail'];
        $admin->password = $_POST['pass'];
        $admin->phone = $_POST['phone'];
        $admin->address = $_POST['address'];
        $admin->status = 'Active'; // Default status
        $admin->type = $_POST['type']; // Get type from form (Admin or Staff)
        // Check if the email already exists in 'admins' table
        if ($admin->isEmailExists($admin->email, 'admins')) {
            echo "Email already exists. Please use a different email.";
            return;
        }
    
        // Insert the new admin into the database
        if ($admin->insert()) {
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
        $adminmodel = new adminModel;
    
        $id = $_POST['id']; // Dữ liệu từ hidden input
        $adminmodel->password = $_POST['password']; // Mật khẩu mới

    
        if ($adminmodel->update($id)) {
            $this->index(); // Quay lại danh sách
        } else {
            echo "Lỗi khi cập nhật mật khẩu.";
        }
    }
    public function login() {
        if (isset($_SESSION['admin_id'])) {  // Giả sử 'user' là key lưu thông tin đăng nhập
            header("Location: ./index.php?controller=home&action=index");
            exit();
        }
        include './Views/Login.php';
    }
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $adminmodel = new AdminModel();
            $admin = $adminmodel->login($email, $password);

            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
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
            
            
            $adminmodel = new AdminModel();
            
            // Call a method to update the brand's status
            if ($adminmodel->delete($id)) {
                header("Location: ./index.php?controller=admin&action=index");
                exit();
            } else {
                echo "Error delete.";
            }
        }
    }
}
