<?php
require_once './Models/Category_Model.php';
class CategoryController {
    public function index() {
        
        $categorymodel = new CategoryModel();
    
        // If 'status' is not set in the URL, redirect to the URL with 'status=all'
        if (empty($_GET['status'])) {
            header("Location: ./index.php?controller=category&action=index&status=all");
            exit();
        }
    
        // Get the status, defaulting to 'all' if not set
        $status = $_GET['status'] ?? 'all'; 
        $categories = $categorymodel->getByStatus($status); // Fetch  based on status
    
        include './Views/ListCategories.php';
    }
    
    public function toggleStatus() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status'];
            
            
            $categorymodel = new CategoryModel();
            
            // Call a method to update the brand's status
            if ($categorymodel->updateStatus($id, $status)) {
                header("Location: ./index.php?controller=category&action=index");
                exit();
            } else {
                echo "Error updating category status.";
            }
        }
    }
    public function add() {
        include './Views/AddCategory.php';
    }

    public function store() {
        
        $product = new CategoryModel;
    
        $product->name = $_POST['name'];
        $product->slug = $_POST['slug'];
    
        if ($product->insert()) {
            $this->index(); // ✅ đúng
            // header("Location: ./index.php?controller=brand&action=index"); // Chuyển hướng về danh sách sản phẩm
        } else {
            echo "Lỗi khi thêm sản phẩm.";
        }
    }
    public function update() {
        
        $categorymodel = new CategoryModel;
    
        $id = $_POST['id']; // Dữ liệu từ hidden input
        $categorymodel->name = $_POST['name'];
        $categorymodel->slug = $_POST['slug'];
    
        if ($categorymodel->update($id)) {
            $this->index(); // Quay lại danh sách
        } else {
            echo "Lỗi khi cập nhật thương hiệu.";
        }
    }
    public function edit() {
        
        $categorymodel = new CategoryModel;
    
        $id = $_GET['id']; // Lấy id từ URL
        $category = $categorymodel->getById($id); // Gọi hàm lấy dữ liệu trong model
    
        include './Views/EditCategory.php'; // Truyền biến $brand sang View
    }
}

?>