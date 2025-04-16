<?php
require_once './Models/Brand_Model.php';
class BrandController {
    public function index() {
        $brandmodel = new BrandModel();
    
        // If 'status' is not set in the URL, redirect to the URL with 'status=all'
        if (empty($_GET['status'])) {
            header("Location: ./index.php?controller=brand&action=index&status=all");
            exit();
        }
    
        // Get the status, defaulting to 'all' if not set
        $status = $_GET['status'] ?? 'all'; 
        $brands = $brandmodel->getByStatus($status); // Fetch brands based on status
    
        include './Views/ListBrands.php';
    }
    
    
    public function toggleStatus() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status'];
            
            
            $brandmodel = new BrandModel();
            
            // Call a method to update the brand's status
            if ($brandmodel->updateStatus($id, $status)) {
                header("Location: ./index.php?controller=brand&action=index");
                exit();
            } else {
                echo "Error updating brand status.";
            }
        }
    }
    public function add() {
        include './Views/AddBrand.php';
    }
    public function store() {
        $product = new BrandModel;
    
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
        $brandmodel = new BrandModel;
    
        $id = $_POST['id']; // Dữ liệu từ hidden input
        $brandmodel->name = $_POST['name'];
        $brandmodel->slug = $_POST['slug'];
    
        if ($brandmodel->update($id)) {
            $this->index(); // Quay lại danh sách
        } else {
            echo "Lỗi khi cập nhật thương hiệu.";
        }
    }
    public function edit() {
        $brandmodel = new BrandModel();
    
        $id = $_GET['id']; // Lấy id từ URL
        $brand = $brandmodel->getById($id); // Gọi hàm lấy dữ liệu trong model
    
        include './Views/EditBrand.php'; // Truyền biến $brand sang View
    }
    
}
?>