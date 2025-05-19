<?php
    require_once './Models/Order_Model.php';
    require_once './Models/Product_Model.php';
    require_once './Models/User_Model.php';
    require_once './Models/Review_Model.php';
class OrderController {
    public function index() {
        $ordermodel = new OrderModel();
        $usermodel = new UserModel();
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
    public function detail() {
        $ordermodel = new OrderModel();
        $productModel = new ProductModel();
        $reviewModel = new ReviewModel();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "Order ID is required.";
            return;
        }

        $order = $ordermodel->getOrderById($id);
        $orderDetails = $ordermodel->getOrderDetail($id);
        $reviews = $reviewModel->getReviewsByOrderId($id);

        include './Views/OrderDetail.php';
    }
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;
    
            $validStatuses = ['Processing', 'Confirmed', 'Shipping', 'Delivered', 'Cancelled'];
    
            if ($orderId && in_array($status, $validStatuses)) {
                $ordermodel = new OrderModel();
                $ordermodel->updateStatus($orderId, $status);
            }
    
            // Redirect lại trang chi tiết đơn hàng
            header("Location: ./index.php?controller=order&action=detail&id=" . urlencode($orderId));
            exit;
        }
    }
    
}
