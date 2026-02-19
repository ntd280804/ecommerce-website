<?php
    require_once './Models/Order_Model.php';
    require_once './Models/Product_Model.php';
    require_once './Models/User_Model.php';

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
        $orders = $ordermodel->getByStatus($status);
        include './Views/ListOrders.php';
    }
    public function detail() {
        $ordermodel = new OrderModel();
        $productModel = new ProductModel();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "Order ID is required.";
            return;
        }

        $order = $ordermodel->getOrderById($id);
        $orderDetails = $ordermodel->getOrderDetail($id);

        include './Views/OrderDetail.php';
    }
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;
    
            $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled', 'refunded'];
            
            if ($orderId && in_array($status, $validStatuses)) {
                $ordermodel = new OrderModel();
                
                try {
                    $result = $ordermodel->updateStatus($orderId, $status);
                    
                    if ($result) {
                        // Success - redirect with success message
                        $_SESSION['success_message'] = "Cập nhật trạng thái đơn hàng thành công!";
                        header("Location: ./index.php?controller=order&action=detail&id=" . urlencode($orderId));
                        exit;
                    } else {
                        // Failed - redirect with error message
                        $_SESSION['error_message'] = "Không thể cập nhật trạng thái đơn hàng!";
                        header("Location: ./index.php?controller=order&action=detail&id=" . urlencode($orderId));
                        exit;
                    }
                } catch (Exception $e) {
                    // Exception - redirect with error message
                    $_SESSION['error_message'] = "Lỗi: " . $e->getMessage();
                    header("Location: ./index.php?controller=order&action=detail&id=" . urlencode($orderId));
                    exit;
                }
            } else {
                // Invalid input
                $_SESSION['error_message'] = "Dữ liệu không hợp lệ!";
                header("Location: ./index.php?controller=order&action=detail&id=" . urlencode($orderId));
                exit;
            }
        }
    }
}
