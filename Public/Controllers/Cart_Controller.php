<?php
require_once("../Config/Database.php"); 
require_once ("./Models/Cart_Model.php");
require_once("./Models/ProductVariant_Model.php");

class CartController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login.html");
            exit();
        }
        $cartModel = new CartModel();

        $cartItems = []; // Ensure this variable is always initialized
        $totalAmount = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['user_role'] ?? 'Default';
            $cartItems = $cartModel->getCartItems($userId,$userRole);
            $totalAmount = $cartModel->getCartTotal($userId,$userRole);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
        }

        // Pass data to view
        include './Views/ShopingCart.php';
    }

    public function addcart(){
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login.html");
            exit();
        }

        if (isset($_POST['product_id']) && isset($_POST['qty'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['qty']);
            $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : null;
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['user_role'] ?? 'Default';

            // Validate variant exists and belongs to product if variant_id is provided
            if ($variantId) {
                $variantModel = new ProductVariantModel();
                $variant = $variantModel->getById($variantId);
                if (!$variant || $variant['product_id'] != $productId) {
                    $_SESSION['error'] = "Invalid product variant selected.";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }

            $cartModel = new CartModel();
            $result = $cartModel->addToCart($userId, $productId, $quantity, $variantId);
            
            if (!$result['success']) {
                if (isset($result['available_stock'])) {
                    $_SESSION['error'] = "Only " . $result['available_stock'] . " items left in stock!";
                } else {
                    $_SESSION['error'] = $result['message'];
                }
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
            
            $totalAmount = $cartModel->getCartTotal($userId, $userRole);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
            
            // Redirect to cart page or back to product page
            if (isset($_POST['buy_now'])) {
                header("Location: ./checkout.html");
            } else {
                header("Location: ./cart.html");
            }
            exit();
        } else {
            $_SESSION['error'] = "Missing product data!";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    public function updatecart() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login.html");
            exit();
        }

        // Check and get data from form
        if (isset($_POST['product_id']) && isset($_POST['qty'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['qty']);
            $variantId = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : null;
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['user_role'] ?? 'Default';
            
            // Update cart
            $cartModel = new CartModel();
            
            // If quantity is 0 or negative, remove the item
            if ($quantity <= 0) {
                $cartModel->removeFromCart($userId, $productId, $variantId);
            } else {
                $cartModel->updateCartItemQuantity($userId, $productId, $quantity, $variantId);
            }
            
            $totalAmount = $cartModel->getCartTotal($userId, $userRole);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId);

            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
            
            // Return JSON response for AJAX requests
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'totalAmount' => number_format($totalAmount, 2),
                    'totalQuantity' => $totalQuantityAmount
                ]);
                exit();
            }
            
            // Redirect back to cart page
            header("Location: ./cart.html");
            exit();
        } else {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Missing product data!']);
                exit();
            }
            $_SESSION['error'] = "Missing product data!";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }


    public function deletecart() {
        if (!isset($_SESSION['user_id'])) {
            // If not logged in, redirect to login page
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'redirect' => './login.html']);
                exit();
            }
            header("Location: ./login.html");
            exit();
        }

        if (isset($_GET['product_id'])) {
            $productId = intval($_GET['product_id']);
            $variantId = isset($_GET['variant_id']) ? intval($_GET['variant_id']) : null;
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['user_role'] ?? 'Default';
            $cartModel = new CartModel();
            
            // Remove the item from cart
            $cartModel->removeFromCart($userId, $productId, $variantId);
            
            // Get updated cart totals
            $totalAmount = $cartModel->getCartTotal($userId, $userRole);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId);
            
            // Update session
            $_SESSION['totalAmount'] = $totalAmount;
            $_SESSION['totalQuantityAmount'] = $totalQuantityAmount;
            
            // Return JSON response for AJAX requests
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'totalAmount' => number_format($totalAmount, 2),
                    'totalQuantity' => $totalQuantityAmount
                ]);
                exit();
            }
            
            // Redirect back to cart page
            $_SESSION['success'] = "Product removed from cart successfully.";
            header("Location: ./cart.html");
            exit();
        } else {
            echo "Missing product data to delete!";
        }
    }

}
