<?php
require_once("../Config/Database.php"); 
require_once ("./Models/Cart_Model.php");

class CartController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./dang-nhap.html");
            exit();
        }
        $cartModel = new CartModel();

        $cartItems = []; // ✅ Đảm bảo biến này luôn được khởi tạo
        $totalAmount = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['user_role'] ?? 'Default';
            $cartItems = $cartModel->getCartItems($userId,$userRole);
            $totalAmount = $cartModel->getCartTotal($userId,$userRole);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId, $userRole);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
        }

        // Pass the data to the view
        include './Views/ShopingCart.php';
    }

    public function addcart(){
        if (!isset($_SESSION['user_id'])) {
    header("Location: ./dang-nhap.html");
    exit();
}

        if (isset($_POST['product_id']) && isset($_POST['qty'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['qty']);
            $userId = $_SESSION['user_id'];

            $cartModel = new CartModel();
            $cartModel->addToCart($userId, $productId, $quantity);
            $totalAmount = $cartModel->getCartTotal($userId, $userRole);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId, $userRole);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
            // Chuyển hướng về trang giỏ hàng
            header("Location: ./gio-hang.html");

            exit();
        } else {
            echo "Thiếu dữ liệu sản phẩm!";
        }
    }
    public function updatecart()
{
    if (!isset($_SESSION['user_id'])) {
    header("Location: .//dang-nhap.html");
    exit();
}

    // Kiểm tra và lấy dữ liệu từ form
    if (isset($_POST['product_id']) && isset($_POST['qty'])) {
        $productId = intval($_POST['product_id']);
        $quantity = intval($_POST['qty']);
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'] ?? 'Default'; // Lấy vai trò người dùng, mặc định là 'Default'
        // Cập nhật giỏ hàng
        $cartModel = new CartModel();
        $cartModel->updateCartItemQuantity($userId, $productId, $quantity);
        $totalAmount = $cartModel->getCartTotal($userId, $userRole);
        $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId, $userRole);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
        // Chuyển hướng lại trang giỏ hàng để người dùng thấy sự thay đổi
        header("Location: ./gio-hang.html");

        exit();
    } else {
        echo "Thiếu dữ liệu sản phẩm!";
    }
}


    public function deletecart()
    {
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: .//dang-nhap.html");
            exit();
        }

        if (isset($_GET['product_id'])) {
            $productId = intval($_GET['product_id']);
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['user_role'] ?? 'Default'; // Lấy vai trò người dùng, mặc định là 'Default'
            $cartModel = new CartModel();
            $cartModel->removeFromCart($userId, $productId);
            $totalAmount = $cartModel->getCartTotal($userId, $userRole);
        $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId, $userRole);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
            // Chuyển hướng lại trang giỏ hàng
            header("Location: ./gio-hang.html");

            exit();
        } else {
            echo "Thiếu dữ liệu sản phẩm để xóa!";
        }
    }

}
