<?php
    require_once("../Config/Database.php"); 
    require_once ("./Models/Order_Model.php");
class OrderController {
    public function checkout() {
    
        $ordermodel = new OrderModel();

        include './Views/Checkout.php';
    }

}
