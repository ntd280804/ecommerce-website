<?php

require_once("../Config/Database.php");
require_once("./Models/Review_Model.php");
require_once("./Models/Order_Model.php");

class ReviewController {
    public function index() {
        // Hiển thị danh sách các đánh giá (nếu cần)
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
    header("Location: .//dang-nhap.html");
    exit();
}
        $reviewModel = new ReviewModel();
        $orderModel = new OrderModel();
    
        if (!isset($_SESSION['user_id'])) {
            echo "Vui lòng đăng nhập để đánh giá.";
            return;
        }
    
        $userId = $_SESSION['user_id'];
        $orderId = $_GET['order_id'] ?? null;
    
        if (!$orderId) {
            echo "Order ID không hợp lệ.";
            return;
        }
    
        // Kiểm tra xem đơn hàng đã được đánh giá chưa
        if ($reviewModel->hasReviewedOrder($userId, $orderId)) {
            echo "Bạn đã đánh giá đơn hàng này.";
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = $_POST['rating'] ?? 0;
            $content = $_POST['content'] ?? '';
    
            // Lấy tất cả productId từ order
            $productIds = $orderModel->getProductIdsByOrderId($orderId);
    
            // Thêm đánh giá cho từng sản phẩm trong đơn hàng
            foreach ($productIds as $productId) {
                $reviewModel->createReview($userId, $productId, $orderId, $rating, $content);
            }
    
            // Điều hướng về trang chi tiết đơn hàng
            header("Location: ./don-hang/$orderId.html");
exit;

            exit;
        }
    
        // Lấy thông tin đơn hàng để hiển thị
        $order = $orderModel->getOrderById($orderId);
    
        include './Views/CreateReview.php'; // Hiển thị form đánh giá
    }
    
}
