<?php
class BaseController {
    protected function view($view, $data = []) {
        $viewPath = '../app/views/' . $view . '.php';
        $layoutPath = '../app/views/layouts/main.php';

        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            require_once $viewPath;
            $content = ob_get_clean();
            require_once $layoutPath;
        } else {
            die("View file not found.");
        }
    }
}
