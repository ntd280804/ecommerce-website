<?php
require_once __DIR__ . '/../../Core/Controller.php';

class HomePageController extends Controller {
    public function index() {
        $this->view('HomePage');
    }
    public function Products_list() {
        $this->view('products');
    }
}
