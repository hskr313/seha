<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/config.php';

// Autoload pour les autres classes
spl_autoload_register(function ($class) {
    $paths = [
        '../app/controllers/' . $class . '.php',
        '../app/repositories/' . $class . '.php',
        '../app/entities/' . $class . '.php',
        '../core/' . $class . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$url = explode('/', $url);

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
