<?php
require_once("../Config/Database.php"); 
require_once("./Models/Order_Model.php");
require_once("./Models/Cart_Model.php");
require_once("./Models/Product_Model.php");
require_once("./Models/Review_Model.php");
class OrderController {
    public function index(){
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: ./index.php?controller=user&action=login");
            exit();
        }
        $ordermodel = new OrderModel();

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $orders = $ordermodel->getOrdersByUserId($userId); // Lấy danh sách đơn hàng của người dùng
        }       
        include './Views/ListOrder.php'; // Hiển thị giao diện đơn hàng
    }
    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./index.php?controller=user&action=login");
            exit();
        }

        $ordermodel = new OrderModel();
        $cartModel = new CartModel();

        $cartItems = [];
        $totalAmount = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartItems = $cartModel->getCartItems($userId);
            $totalAmount = $cartModel->getCartTotal($userId);
        }

        include './Views/Checkout.php'; // Hiển thị giao diện checkout
    }

    public function placeorder() {
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: ./index.php?controller=user&action=login");
            exit();
        }
        $ordermodel = new OrderModel();
        $cartModel = new CartModel();

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartItems = $cartModel->getCartItems($userId);
            $totalAmount = $cartModel->getCartTotal($userId);

            // Tạo đơn hàng
            $orderId = $ordermodel->createOrder($userId, $totalAmount, $cartItems);

            // Xóa giỏ hàng sau khi đặt hàng thành công
            if ($orderId) {
                $cartModel->clearCart($userId);
                header("Location: ./index.php?controller=home");
                exit;
            }
        }
    }
    public function detail() {
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: ./index.php?controller=user&action=login");
            exit();
        }
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
}

