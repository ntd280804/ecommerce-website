<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Product_Model.php");
class ProductController {
    public function index() {
        // Điều hướng tới trang dashboard admin
        $productmodel = new ProductModel();
        if (isset($_GET['category'])) {
            $category = $_GET['category'];
            $products = $productmodel->getByCategory($category);
        } else {
            $products = $productmodel->getAllActive(); // Lấy tất cả sản phẩm đang hoạt động
        }
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