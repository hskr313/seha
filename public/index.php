<?php
require_once '../config/config.php';
require_once '../core/BaseController.php';
require_once '../core/BaseModel.php';

// Autoload des classes
spl_autoload_register(function ($class) {
    if (file_exists('../app/controllers/' . $class . '.php')) {
        require_once '../app/controllers/' . $class . '.php';
    }
    if (file_exists('../app/models/' . $class . '.php')) {
        require_once '../app/models/' . $class . '.php';
    }
});

// Gestion de la route
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$url = explode('/', $url);

// Contrôleur par défaut
$controller = isset($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$method = isset($url[1]) ? $url[1] : 'index';

if (class_exists($controller)) {
    $controller = new $controller;
    if (method_exists($controller, $method)) {
        unset($url[0]);
        unset($url[1]);
        call_user_func_array([$controller, $method], $url ? array_values($url) : []);
    } else {
        echo "Method $method not found!";
    }
} else {
    echo "Controller $controller not found!";
}
