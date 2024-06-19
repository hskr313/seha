<?php
class BaseController {
    protected function view($view, $data = [], $layout = 'main') {
        $viewPath = "../app/views/$view.php";
        $layoutPath = "../app/views/layouts/$layout.php";

        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            require_once $viewPath;
            $content = ob_get_clean();
            require_once $layoutPath;
        } else {
            die("View file not found: $viewPath");
        }
    }
}
