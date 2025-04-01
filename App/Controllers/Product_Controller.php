<?php
require_once __DIR__ . '/../../Core/Controller.php';
require_once __DIR__ . '/../Models/Product.php';

class ProductController extends Controller {

    // This action displays the products list
    public function index() {
        $productModel = new Product();  // Create a new Product model instance
        $products = $productModel->getAllProducts();  // Fetch products from the model

        // Pass the products array to the view
        $this->view('products', ['products' => $products]);
    }
}
