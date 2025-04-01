<?php
require_once __DIR__ . '/../App/Controllers/HomePage_Controller.php';
require_once __DIR__ . '/../App/Controllers/Product_Controller.php';

$action = $_GET["action"];
if($action ==="index")
{
    $controller = new HomePageController();
    $controller->index();
}
elseif ($action === "product")
{
    $controller = new ProductController();
    $controller->index();
}
elseif ($action === "admin")
{
    $controller = new ProductController();
    $controller->index();
}