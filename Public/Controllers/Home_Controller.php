<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Product_Model.php");
    
class HomeController {
    public function index() {
    
        $ProductModel = new ProductModel();
        $topdiscountedproduct = $ProductModel->getTopDiscounted(5); // Fetch  based on status
        $topratedproduct = $ProductModel->getTopRated(); // Fetch  based on status
        include './Views/HomePage.php'; // 👈 View gọi Header.php, bây giờ biến đã có
        
        
    }
    public function Error404() {
        // Điều hướng tới trang dashboard admin
        include './Views/HomePage.php';
        //include './Views/404.php';
    }
    public function contact() {
        // Điều hướng tới trang dashboard admin
        include './Views/Contact.php';
        //include './Views/404.php';
    }
}
