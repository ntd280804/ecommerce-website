<?php
class HomeController {
    public function index() {
        // Điều hướng tới trang dashboard admin
        include './Views/HomePage.php';
    }
    public function Error404() {
        // Điều hướng tới trang dashboard admin
        include './Views/HomePage.php';
        //include './Views/404.php';
    }
    public function shopgrid() {
        // Điều hướng tới trang dashboard admin
        include './Views/ShopGrid.php';
    }
}
