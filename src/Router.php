<?php
class Router
{
    public static $cacheRoutes = true;
    
    public static $classPrefix = "";
    
    public static $classDir = "";
    
    public static function route($requestURI, $baseURL, $classDir, $classPrefix = "", $options = array())
    {
        self:: $classPrefix = $classPrefix;
        
        self::$classDir = $classDir;
        
        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen($_SERVER['QUERY_STRING']) - 1);
        }
        
        // Trim the base part of the URL
        $requestURI = substr($requestURI, strlen(parse_url($baseURL, PHP_URL_PATH)));
        
        $routes = self::getRoutes();
        
        if (isset($options['view'], $routes[$options['view']])) {
            $options['model'] = $routes[$options['view']];
            return $options;
        }

        if (empty($requestURI)) {
            // Default view/homepage
            $options['model'] = "Home_View";
            return $options;
        }

        foreach ($routes as $route_exp=>$model) {
            if ($route_exp[0] == '/' && preg_match($route_exp, $requestURI, $matches)) {
                $options += $matches;
                $options['model'] = $model;
                return $options;
            }
        }
        
        return $options;
    }
    
    public static function getRoutes()
    {
        if (!self::$cacheRoutes) {
            return self::compileRoutes();
        }
        
        if (file_exists(self::getCachePath())) {
            $cache = file_get_contents(self::getCachePath());
            return unserialize($cache);
        }
        
        return self::cacheRoutes();
        
    }
    
    public static function cacheRoutes()
    {
        $routes = self::compileRoutes();
        
        file_put_contents(self::getCachePath(), serialize($routes));
        
        return $routes;
    }
    
    public static function getCachePath()
    {
        return sys_get_temp_dir() . "/" . __CLASS__ . "_Cache.php";
    }
    
    public static function compileRoutes()
    {
        $routes = array();
        
        //Directory itterator
        $directory = new DirectoryIterator(dirname(__FILE__));
        
        //Compile all the routes.
        foreach ($directory as $file) {
            if ($file->getType() == 'dir' && !$file->isDot()) {
                $class = self::$classPrefix . $file->getFileName() . "_Router";
                if (file_exists(self::$classDir . str_replace('_', '/', $class). ".php")
                    && class_exists($class)) {
                    $routes += call_user_func($class . "::getRoutes");
                }
            }
        }
        
        return $routes;
    }
}