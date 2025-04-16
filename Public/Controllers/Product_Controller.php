<?php
class ProductController {
    public function index() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            // Maybe redirect or show an error
            include './Views/HomePage.php';
        }

        require_once("../Config/Database.php");
        require_once ("./Models/Product_Model.php");
        $productmodel = new ProductModel();
        $product = $productmodel->getById($id); // Fetch  based on status
        $images = $productmodel->getImagesById($product['id']);
        include './Views/ProductDetail.php';
    }
}
