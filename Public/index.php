<?php
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

switch ($controller) {
    case 'home':
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->$action();
        break;
    case 'product':
            require_once 'Controllers/Product_Controller.php';
            $home = new ProductController();
            $home->$action();
            break;
    case 'user':
        require_once 'Controllers/User_Controller.php';
        $home = new UserController();
        $home->$action();
        break;
    default:
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->Error404(); // Handle 404 error
        break;
}
