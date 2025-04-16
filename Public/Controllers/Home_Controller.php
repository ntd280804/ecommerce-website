<?php
class HomeController {
    public function index() {
    
        require_once("../Config/Database.php"); 
        require_once ("./Models/Product_Model.php");
        $productmodel = new ProductModel();
        $products = $productmodel->getAllActive(); // Fetch  based on status
        $topratedproduct = $productmodel->getAllActive(); // Fetch  based on status
        //$topratedproduct = $productmodel->getTopRated(); // Fetch  based on status
        include './Views/HomePage.php';
    }
    public function Error404() {
        // Điều hướng tới trang dashboard admin
        include './Views/HomePage.php';
        //include './Views/404.php';
    }
    public function shopgrid() {
        // Điều hướng tới trang dashboard admin
        require_once("../Config/Database.php"); 
        require_once ("./Models/Product_Model.php");
        $productmodel = new ProductModel();
        $products = $productmodel->getAllActive(); // Fetch  based on status
        include './Views/ShopGrid.php';
    }
    public function ShopCart() {
        // Điều hướng tới trang dashboard admin
        include './Views/ShopingCart.php';
    }
}
