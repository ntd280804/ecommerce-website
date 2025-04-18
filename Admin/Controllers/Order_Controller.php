<?php
    require_once './Models/Order_Model.php';
class OrderController {
    public function index() {
        $ordermodel = new OrderModel();
    
        // If 'status' is not set in the URL, redirect to the URL with 'status=all'
        if (empty($_GET['status'])) {
            header("Location: ./index.php?controller=order&action=index&status=all");
            exit();
        }
    
        // Get the status, defaulting to 'all' if not set
        $status = $_GET['status'] ?? 'all'; 
        $orders = $ordermodel->getByStatus($status); // Fetch brands based on status
        include './Views/ListOrders.php';
    }
}
