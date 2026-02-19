<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Product_Model.php");
    
class HomeController {
    public function index() {
    
        $ProductModel = new ProductModel();
        $topdiscountedproduct = $ProductModel->getTopDiscounted(5); // Fetch top discounted products
        $topratedproduct = $ProductModel->getTopRated(); // Fetch top rated products
        include './Views/HomePage.php'; // View includes Header.php with available variables
        
        
    }
    public function Error404() {
        // Redirect to home page
        include './Views/HomePage.php';
        //include './Views/404.php';
    }
    public function contact() {
        // Redirect to contact page
        include './Views/Contact.php';
        //include './Views/404.php';
    }
}
