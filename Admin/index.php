<?php
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

switch ($controller) {
    case 'home':
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->$action();
        break;

    case 'brand':
        require_once 'Controllers/Brand_Controller.php';
        $brand = new BrandController();
        $brand->$action();
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

    case 'review':
        require_once 'Controllers/Review_Controller.php';
        $review = new ReviewController();
        $review->$action();
        break;

    case 'user':
        require_once 'Controllers/User_Controller.php';
        $user = new UserController();
        $user->$action();
        break;

    default:
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->Error404(); // Handle 404 error
        break;
}
