<?php
class BrandController {
    public function index() {
        require_once './Models/Brand_Model.php';
        $brandModel = new BrandModel();
        $brands = $brandModel->getAll(); // Lấy danh sách thương hiệu từ model
        
        include './Views/ListBrands.php';
    }
    public function add() {
        include './Views/AddBrand.php';
    }
    public function delete() {
        $id = $_GET['id'] ?? null;
    
        if ($id) {
            require_once './Models/Brand_Model.php';
            $brandModel = new BrandModel();
            if ($brandModel->delete($id)) {
                $this->index(); // ✅ đúng
                // header("Location: ./index.php?controller=brand&action=index"); // Chuyển hướng về danh sách sản phẩm
            } else {
                echo "Lỗi khi xóa sản phẩm.";
            }
        }
        
        exit();
    }
    

    public function store() {
        require_once './Models/Brand_Model.php';
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
}
