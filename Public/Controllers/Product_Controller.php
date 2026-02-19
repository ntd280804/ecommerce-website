<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Product_Model.php");
class ProductController {
    public function index() {
        $ProductModel = new ProductModel();
        
        $tukhoa = $_GET['tukhoa'] ?? '';
        $category = $_GET['category'] ?? null;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 9;
        $offset = ($page - 1) * $limit;
        $sort = $_GET['sort'] ?? 'default';
    
        if (!empty($tukhoa)) {
            // Tìm kiếm sản phẩm có phân trang và sắp xếp
            $products = $ProductModel->searchProductsWithPagination($tukhoa, $limit, $offset, $sort);
            $totalProducts = $ProductModel->countSearchProducts($tukhoa);
        } elseif ($category) {
            // Lọc theo danh mục có phân trang và sắp xếp
            $products = $ProductModel->getByCategoryWithPagination($category, $limit, $offset, $sort);
            $totalProducts = $ProductModel->countByCategory($category);
        } else {
            // Lấy tất cả sản phẩm có phân trang và sắp xếp
            $products = $ProductModel->getAllActiveWithPagination($limit, $offset, $sort);
            $totalProducts = $ProductModel->countAllActive();
        }
    
        $totalPages = ceil($totalProducts / $limit);
        
        include './Views/ProductGrid.php';
    }    
    public function detail() {
        $slug = $_GET['slug'] ?? null;
        if (!$slug) {
            // Maybe redirect or show an error
            include './Views/HomePage.php';
        }
        
        $ProductModel = new ProductModel();
        $product = $ProductModel->getByslug($slug);
        if (!$product) {
        include './Views/HomePage.php';
        return;
    } // Fetch  based on status
        $images = $ProductModel->getImagesByslug($product['slug']);
        //$ProductModel->generateStaticProduct($product['slug'], __DIR__ . '/../product/' . $product['slug'] . '.html');
        include './Views/ProductDetail.php';
    }
    public function search() {
        $tukhoa = $_GET['tukhoa'] ?? '';
        $ProductModel = new ProductModel();
    
        if (!empty($tukhoa)) {
            $products = $ProductModel->searchProducts($tukhoa);
        } else {
            $products = [];
        }
    
        include './Views/ProductGrid.php';
    }
    
}
?>