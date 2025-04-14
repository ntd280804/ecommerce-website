<?php
class CategoryController {
    public function index() {
        require_once './Models/Category_Model.php';
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAll(); // Lấy danh sách danh mục từ model
        include './Views/ListCategories.php';
    }
    public function add() {
        include './Views/AddCategory.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
    
        if ($id) {
            require_once './Models/Category_Model.php';
            $CategoryModel = new CategoryModel();
            if ($CategoryModel->delete($id)) {
                $this->index(); // ✅ đúng
                // header("Location: ./index.php?controller=category&action=index"); // Chuyển hướng về danh sách sản phẩm
            } else {
                echo "Lỗi khi xóa sản phẩm.";
            }
        }
        
        exit();
    }

    public function store() {
        require_once './Models/Category_Model.php';
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
}

