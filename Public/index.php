<?php
session_name("user_session");
session_start();

// --- Đồng bộ role từ DB nếu đã đăng nhập ---
if (isset($_SESSION['user_id'])) {
    require_once("../Config/Database.php");  // Đảm bảo đúng path
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $newRole = $stmt->fetchColumn();

    if ($newRole && $newRole !== ($_SESSION['user_role'] ?? '')) {
        $_SESSION['user_role'] = $newRole; // Cập nhật session nếu role đổi
    }
}


$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

switch ($controller) {
    case 'home':
        require_once 'Controllers/Home_Controller.php';
        $controller = new HomeController();
        $controller->$action();
        break;
    case 'product':
            require_once 'Controllers/Product_Controller.php';
            $controller = new ProductController();
            $controller->$action();
            break;
    case 'user':
        require_once 'Controllers/User_Controller.php';
        $controller = new UserController();
        $controller->$action();
        break;
    case 'cart':
        require_once 'Controllers/Cart_Controller.php';
        $controller = new CartController();
        $controller->$action();
        break;
    case 'order':
        require_once 'Controllers/Order_Controller.php';
        $controller = new OrderController();
        $controller->$action();
        break;
    case 'review':
        require_once 'Controllers/Review_Controller.php';
        $controller = new ReviewController();
        $controller->$action();
        break;
    default:
        require_once 'Controllers/Home_Controller.php';
        $controller = new HomeController();
        $controller->Error404(); // Handle 404 error
        break;
}
