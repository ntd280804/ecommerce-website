<?php
class Controller {
    public function view($view, $data = []) {
        extract($data, EXTR_SKIP); // Prevent overwriting existing variables
        $file = __DIR__ . '/../App/Views/' . $view . '.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            die("View không tồn tại: " . $file);
        }
    }
}

