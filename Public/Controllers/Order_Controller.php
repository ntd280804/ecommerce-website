<?php
require_once("../Config/Database.php"); 
require_once("./Models/Order_Model.php");
require_once("./Models/Cart_Model.php");

class OrderController {
    public function checkout() {
        $ordermodel = new OrderModel();
        $cartModel = new CartModel();

        $cartItems = [];
        $totalAmount = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartItems = $cartModel->getCartItems($userId);
            $totalAmount = $cartModel->getCartTotal($userId);
        }

        include './Views/Checkout.php'; // hiển thị giao diện
    }

    public function placeOrder() {
        $ordermodel = new OrderModel();
        $cartModel = new CartModel();

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartItems = $cartModel->getCartItems($userId);
            $totalAmount = $cartModel->getCartTotal($userId);

            if (!empty($cartItems)) {
                try {
                    $orderId = $ordermodel->placeOrder($userId, $cartItems, $totalAmount);
                    $cartModel->clearCart($userId);
                    echo "Đặt hàng thành công! Mã đơn hàng của bạn là: " . $orderId;
                    exit();
                } catch (Exception $e) {
                    echo "Đặt hàng thất bại: " . $e->getMessage();
                }
            } else {
                echo "Giỏ hàng trống.";
            }
        } else {
            header("Location: login.php");
            exit();
        }
    }
}
