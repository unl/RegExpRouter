<?php
if (file_exists(dirname(__FILE__) . '/config.inc.php')) {
    require_once dirname(__FILE__) . '/config.inc.php';
} else {
    require dirname(__FILE__) . '/config.sample.php';
}

session_start();

if (isset($_GET['model'])) {
    unset($_GET['model']);
}

$router = new RegExpRouter(array('baseURL' => Example_Controller::$url, 'classDir' => dirname(__FILE__) . "/src/Example/", "classPrefix" => "Example_"));

$wub = new Example_Controller($router->route($_SERVER['REQUEST_URI'], $_GET));