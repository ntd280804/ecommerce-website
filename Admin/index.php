<?php
session_start(); // Bắt đầu session

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Nếu không phải trang login và chưa đăng nhập => chuyển hướng về login
if ($controller !== 'user' && $action !== 'login') {
    if (!isset($_SESSION['admin_name'])) {
        header('Location: index.php?controller=user&action=login');
        exit();
    }
}

switch ($controller) {
    case 'user':
        require_once 'Controllers/User_Controller.php';
        $user = new UserController();
        $user->$action();
        break;
        
    case 'home':
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->$action();
        break;

    case 'category':
        require_once 'Controllers/Category_Controller.php';
        $category = new CategoryController();
        $category->$action();
        break;

    case 'product':
        require_once 'Controllers/Product_Controller.php';
        $product = new ProductController();
        $product->$action();
        break;

    case 'order':
        require_once 'Controllers/Order_Controller.php';
        $order = new OrderController();
        $order->$action();
        break;


    case 'user':
        require_once 'Controllers/User_Controller.php';
        $user = new UserController();
        $user->$action();
        break;
    case 'admin':
        require_once 'Controllers/Admin_Controller.php';
        $admin = new AdminController();
        $admin->$action();
        break;
    default:
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->Error404(); // 404 not found
        break;
}
