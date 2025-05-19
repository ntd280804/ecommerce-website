<?php
require_once("../Config/Database.php"); 
require_once ("./Models/Cart_Model.php");

class CartController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: ./index.php?controller=user&action=login");
            exit();
        }
        $cartModel = new CartModel();

        $cartItems = []; // ✅ Đảm bảo biến này luôn được khởi tạo
        $totalAmount = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartItems = $cartModel->getCartItems($userId);
            $totalAmount = $cartModel->getCartTotal($userId);
        }

        // Pass the data to the view
        include './Views/ShopingCart.php';
    }

    public function addcart(){
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: ./index.php?controller=user&action=login");
            exit();
        }

        if (isset($_POST['product_id']) && isset($_POST['qty'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['qty']);
            $userId = $_SESSION['user_id'];

            $cartModel = new CartModel();
            $cartModel->addToCart($userId, $productId, $quantity);

            // Chuyển hướng về trang giỏ hàng
            header("Location: ./index.php?controller=cart&action=index");
            exit();
        } else {
            echo "Thiếu dữ liệu sản phẩm!";
        }
    }
    public function updatecart()
{
    if (!isset($_SESSION['user_id'])) {
        // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
        header("Location: ./index.php?controller=user&action=login");
        exit();
    }

    // Kiểm tra và lấy dữ liệu từ form
    if (isset($_POST['product_id']) && isset($_POST['qty'])) {
        $productId = intval($_POST['product_id']);
        $quantity = intval($_POST['qty']);
        $userId = $_SESSION['user_id'];

        // Cập nhật giỏ hàng
        $cartModel = new CartModel();
        $cartModel->updateCartItemQuantity($userId, $productId, $quantity);

        // Chuyển hướng lại trang giỏ hàng để người dùng thấy sự thay đổi
        header("Location: ./index.php?controller=cart&action=index");
        exit();
    } else {
        echo "Thiếu dữ liệu sản phẩm!";
    }
}


    public function deletecart()
    {
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: ./index.php?controller=user&action=login");
            exit();
        }

        if (isset($_GET['product_id'])) {
            $productId = intval($_GET['product_id']);
            $userId = $_SESSION['user_id'];

            $cartModel = new CartModel();
            $cartModel->removeFromCart($userId, $productId);

            // Chuyển hướng lại trang giỏ hàng
            header("Location: ./index.php?controller=cart&action=index");
            exit();
        } else {
            echo "Thiếu dữ liệu sản phẩm để xóa!";
        }
    }

}
