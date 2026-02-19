<?php
require_once(__DIR__ . "/../../Config/Database.php"); 
require_once(__DIR__ . "/../Models/Order_Model.php");
require_once(__DIR__ . "/../Models/Cart_Model.php");
require_once(__DIR__ . "/../Models/Product_Model.php");

class OrderController {
    public function index(){
        if (!isset($_SESSION['user_id'])) {
    header("Location: .//login.html");
    exit();
}
        $ordermodel = new OrderModel();

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $orders = $ordermodel->getOrdersByUserId($userId); // Lấy danh sách đơn hàng của người dùng
            
            // Get order items for each order
            foreach ($orders as &$order) {
                $order['items'] = $ordermodel->getOrderItems($order['id']);
            }
        }       
        include './Views/ListOrder.php'; // Hiển thị giao diện đơn hàng
    }
    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: .//login.html");
            exit();
        }

        $ordermodel = new OrderModel();
        $cartModel = new CartModel();

        $cartItems = [];
        $totalAmount = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $userRole = $_SESSION['user_role'] ?? 'Default';
            $cartItems = $cartModel->getCartItems($userId, $userRole);
            $totalAmount = $cartModel->getCartTotal($userId, $userRole);
        }

        include './Views/Checkout.php'; // Hiển thị giao diện checkout
    }

    public function placeorder() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: .//login.html");
            exit();
        }

        // Get database connection first
        $db = Database::connect();
        
        try {
            // Start transaction
            $db->beginTransaction();
            
            // Log start of order creation
            error_log('Starting order creation process');
            
            // Create models with the same connection
            $ordermodel = new OrderModel($db);
            $cartModel = new CartModel($db);

            $userId = $_SESSION['user_id'];
            error_log('Getting cart items for user: ' . $userId);
            $cartItems = $cartModel->getCartItems($userId);
            error_log('Cart items: ' . print_r($cartItems, true));
            
            $totalAmount = $cartModel->getCartTotal($userId);
            $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId);
            
            error_log("Cart total: $totalAmount, Quantity: $totalQuantityAmount");
            
            // Get data from POST form
            $receiver_name = isset($_POST['receiver_name']) ? trim($_POST['receiver_name']) : '';
            $receiver_phone = isset($_POST['receiver_phone']) ? trim($_POST['receiver_phone']) : '';
            $receiver_address = isset($_POST['receiver_address']) ? trim($_POST['receiver_address']) : '';
            $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'cod';
            
            error_log("Order details - Name: $receiver_name, Phone: $receiver_phone, Address: $receiver_address, Payment: $payment_method");
            
            // Validate required fields
            if (empty($receiver_name) || empty($receiver_phone) || empty($receiver_address)) {
                throw new Exception("Vui lòng điền đầy đủ thông tin nhận hàng");
            }
            
            if (empty($cartItems)) {
                throw new Exception("Giỏ hàng của bạn đang trống");
            }
            
            // Create order
            error_log('Calling createOrder...');
            $orderId = $ordermodel->createOrder($userId, $totalAmount, $cartItems, $receiver_name, $receiver_phone, $receiver_address, $payment_method);
            error_log('Order created with ID: ' . ($orderId ?: 'null'));
            
            if (!$orderId) {
                throw new Exception("Failed to create order - No order ID returned");
            }
            
            // Inventory is already updated by stored procedure for COD orders
            // No need to call updateInventoryForOrderCreation again
            
            // Clear cart after successful order
            $cartModel->clearCart($userId);
            
            // Commit the transaction after all operations are successful
            $db->commit();

            // Clear cart session data
            $_SESSION['totalAmount'] = 0;
            $_SESSION['totalQuantityAmount'] = 0;
            
            // Redirect to order list page after successful order
            header("Location: ../index.php?controller=order");
            exit;
            
        } catch (Exception $e) {
            // Only rollback if we're in a transaction
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            
            // Log detailed error
            $errorMessage = 'Order placement failed: ' . $e->getMessage() . 
                          ' in ' . $e->getFile() . ' on line ' . $e->getLine() . 
                          '\nStack trace: ' . $e->getTraceAsString();
            error_log($errorMessage);
            
            // For debugging, you can uncomment the next line to see the error directly
            // die('Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            
            // Store error message in session and redirect back to checkout
            $_SESSION['error'] = $e->getMessage();
            header("Location: ../index.php?controller=order&error=1");
            exit();
        }
    }
    
    
    public function detail() {
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: .//login.html");
            exit();
        }
        $ordermodel = new OrderModel();
        $ProductModel = new ProductModel();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "Order ID is required.";
            return;
        }

        $order = $ordermodel->getOrderById($id);
        $orderDetails = $ordermodel->getOrderItems($id);
        
        include './Views/OrderDetail.php';
    }
    
    public function updateStatus() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: .//login.html");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $newStatus = $_POST['status'] ?? null;
            
            if ($orderId && $newStatus) {
                try {
                    $ordermodel = new OrderModel();
                    
                    // Update inventory based on status change
                    $ordermodel->updateInventoryForOrderStatusChange($orderId, $newStatus);
                    
                    // Update order status
                    $db = Database::connect();
                    $stmt = $db->prepare("
                        UPDATE orders 
                        SET status = :status, updated_at = NOW() 
                        WHERE id = :id
                    ");
                    $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $_SESSION['success'] = "Order status updated successfully!";
                    
                } catch (Exception $e) {
                    $_SESSION['error'] = "Failed to update order status: " . $e->getMessage();
                }
            }
            
            header("Location: orders.html");
            exit();
        }
    }
}
