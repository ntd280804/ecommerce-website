<?php
class HomeController {
    public function index() {
        // Điều hướng tới trang dashboard admin
        include './Views/Dashboard.php';
    }
    public function Error404() {
        // Điều hướng tới trang dashboard admin
        include './Views/404.php';
    }
}
