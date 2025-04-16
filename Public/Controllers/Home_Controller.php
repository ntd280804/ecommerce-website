<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Product_Model.php");
    
class HomeController {
    public function index() {
    
        $productmodel = new ProductModel();
        $products = $productmodel->getAllActive(); // Fetch  based on status
        $topratedproduct = $productmodel->getTopRated(); // Fetch  based on status
        include './Views/HomePage.php'; // 👈 View gọi Header.php, bây giờ biến đã có
        
        
    }
    public function Error404() {
        // Điều hướng tới trang dashboard admin
        include './Views/HomePage.php';
        //include './Views/404.php';
    }

}
