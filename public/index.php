<?php
session_start();

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

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = str_replace('/seha/public/', '', $url);
$url = rtrim($url, '/');
$url = explode('/', $url);

$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'MarketPlaceController';
$method = !empty($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

if (class_exists($controllerName)) {
    $controller = new $controllerName;
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        include '../app/views/404.php';
    }
} else {
    include '../app/views/404.php';
}
