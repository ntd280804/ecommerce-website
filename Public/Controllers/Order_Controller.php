<?php
require_once("../Config/Database.php"); 
require_once("./Models/Order_Model.php");
require_once("./Models/Cart_Model.php");
require_once("./Models/Product_Model.php");
require_once("./Models/Review_Model.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../vendor/autoload.php';
class OrderController {
    public function index(){
        if (!isset($_SESSION['user_id'])) {
    header("Location: .//dang-nhap.html");
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
            header("Location: .//dang-nhap.html");
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
            header("Location: .//dang-nhap.html");
            exit();
        }

        $ordermodel = new OrderModel();
        $cartModel = new CartModel();

        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'] ?? 'Default';
        $cartItems = $cartModel->getCartItems($userId, $userRole);
        $totalAmount = $cartModel->getCartTotal($userId, $userRole);
        $totalQuantityAmount = $cartModel->getCartTotalQuantity($userId, $userRole);
        // Lấy dữ liệu từ form POST
        $receiver_name = isset($_POST['receiver_name']) ? trim($_POST['receiver_name']) : '';
        $receiver_phone = isset($_POST['receiver_phone']) ? trim($_POST['receiver_phone']) : '';
        $receiver_address = isset($_POST['receiver_address']) ? trim($_POST['receiver_address']) : '';
        $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

        // Tạo đơn hàng, truyền thêm thông tin người nhận và thanh toán
        $orderId = $ordermodel->createOrder($userId,$userRole, $totalAmount, $cartItems, $receiver_name, $receiver_phone, $receiver_address, $payment_method);

        if ($orderId) {
            // Gửi email thông báo đơn hàng mới
            $adminEmail = 'vinhdtq123123123@gmail.com'; // Đổi thành email của bạn

            $mail = new PHPMailer(true);
            
            try {
                //Server settings
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'vinhdtq123123123@gmail.com'; // Đổi thành email gửi
                $mail->Password   = 'uafd aatw ocyo ebfe'; // Đổi thành app password hoặc mật khẩu email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('vinhdtq123123123@gmail.com', 'Shop Notification');
                $mail->addAddress($adminEmail);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Đơn hàng mới từ ' . htmlspecialchars($receiver_name) . ' - Mã đơn: ' . $orderId;

                // Nội dung đơn hàng
                $body = "<h3>Thông tin đơn hàng mới</h3>";
                $body .= "<b>Người nhận:</b> " . htmlspecialchars($receiver_name) . "<br>";
                $body .= "<b>Điện thoại:</b> " . htmlspecialchars($receiver_phone) . "<br>";
                $body .= "<b>Địa chỉ:</b> " . htmlspecialchars($receiver_address) . "<br>";
                $body .= "<b>Phương thức thanh toán:</b> " . htmlspecialchars($payment_method) . "<br>";
                $body .= "<b>Tổng tiền:</b> " . number_format($totalAmount, 2) . " VNĐ<br>";
                $body .= "<h4>Chi tiết sản phẩm:</h4><ul>";
                foreach ($cartItems as $item) {
                    $body .= "<li>" . htmlspecialchars($item['product_name']) . " - SL: " . $item['qty'] . " - Giá: " . number_format($item['price'], 2) . " VNĐ</li>";
                }
                $body .= "</ul>";

                $mail->Body = $body;

                $mail->send();
            } catch (Exception $e) {
                // Có thể ghi log lỗi nếu cần
            }

            $cartModel->clearCart($userId);
            $_SESSION['totalAmount'] = 0;
            $_SESSION['totalQuantityAmount'] = 0;
            header("Location: ./trang-chu.html");
            exit;
        } else {
            // Xử lý khi tạo đơn hàng thất bại (nếu cần)
        }
    }
    
    public function detail() {
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            header("Location: .//dang-nhap.html");
            exit();
        }
        $ordermodel = new OrderModel();
        $ProductModel = new ProductModel();
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

