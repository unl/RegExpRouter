<?php
function autoload($class)
{
    $class = str_replace('_', '/', $class);
    
    $pathArray = explode(PATH_SEPARATOR, get_include_path());

    foreach($pathArray as $path) {
        if (file_exists($path . "/" . $class . '.php')) {
            include $class . '.php';
        }
    }
}

spl_autoload_register("autoload");

set_include_path(
    implode(PATH_SEPARATOR, array(get_include_path())).PATH_SEPARATOR
          . dirname(dirname(__FILE__)) . '/src' . PATH_SEPARATOR
          . dirname(__FILE__) . '/src' . PATH_SEPARATOR
);

ini_set('display_errors', true);

error_reporting(E_ALL);

RegExpRouter::$cacheRoutes = false;

Example_Controller::$url = 'http://localhost/application/vendor/RegExpRouter/Example/';