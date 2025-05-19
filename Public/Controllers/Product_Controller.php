<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Product_Model.php");
class ProductController {
    public function index() {
        $productmodel = new ProductModel();
        
        $tukhoa = $_GET['tukhoa'] ?? '';
        $category = $_GET['category'] ?? null;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 9;
        $offset = ($page - 1) * $limit;
        $sort = $_GET['sort'] ?? 'default';
    
        if (!empty($tukhoa)) {
            // Tìm kiếm sản phẩm có phân trang và sắp xếp
            $products = $productmodel->searchProductsWithPagination($tukhoa, $limit, $offset, $sort);
            $totalProducts = $productmodel->countSearchProducts($tukhoa);
        } elseif ($category) {
            // Lọc theo danh mục có phân trang và sắp xếp
            $products = $productmodel->getByCategoryWithPagination($category, $limit, $offset, $sort);
            $totalProducts = $productmodel->countByCategory($category);
        } else {
            // Lấy tất cả sản phẩm có phân trang và sắp xếp
            $products = $productmodel->getAllActiveWithPagination($limit, $offset, $sort);
            $totalProducts = $productmodel->countAllActive();
        }
    
        $totalPages = ceil($totalProducts / $limit);
        
        include './Views/ProductGrid.php';
    }    
    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            // Maybe redirect or show an error
            include './Views/HomePage.php';
        }
        $productmodel = new ProductModel();
        $product = $productmodel->getById($id); // Fetch  based on status
        $images = $productmodel->getImagesById($product['id']);
        $ratingData = $productmodel->getProductRating($product['id']);
        $productReviews = $productmodel->getProductReviews($product['id']);
        
        include './Views/ProductDetail.php';
    }
    public function search() {
        $tukhoa = $_GET['tukhoa'] ?? '';
        $productmodel = new ProductModel();
    
        if (!empty($tukhoa)) {
            $products = $productmodel->searchProducts($tukhoa);
        } else {
            $products = [];
        }
    
        include './Views/ProductGrid.php';
    }
    
}
?>