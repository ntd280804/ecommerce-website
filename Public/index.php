<?php
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

switch ($controller) {
    case 'home':
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->$action();
        break;

    default:
        require_once 'Controllers/Home_Controller.php';
        $home = new HomeController();
        $home->Error404(); // Handle 404 error
        break;
}
