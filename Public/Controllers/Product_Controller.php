<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Product_Model.php");
class ProductController {
    public function index() {
        $productmodel = new ProductModel();
        $category = $_GET['category'] ?? null;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 9; // Số sản phẩm mỗi trang
        $offset = ($page - 1) * $limit;

        if ($category) {
            $products = $productmodel->getByCategoryWithPagination($category, $limit, $offset);
            $totalProducts = $productmodel->countByCategory($category);
        } else {
            $products = $productmodel->getAllActiveWithPagination($limit, $offset);
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
}
?>