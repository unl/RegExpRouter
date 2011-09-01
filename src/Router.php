<?php
class Router
{
    public static $cacheRoutes = true;
    
    public $classPrefix = "";
    
    public $classDir = "";
    
    private $routes = array();
    
    function __construct($options = array())
    {
        if (!isset($options['baseURL']) || empty($options['baseURL'])) {
            throw new Exception("You must define the baseURL", 500);
        }
        
        if (!isset($options['classDir']) || empty($options['baseURL'])) {
            throw new Exception("You must define the classDir", 500);
        }
        
        if (!isset($options['classPrefix']) || empty($options['classPrefix'])) {
            $options['classPrefix'] = "";
        }
        
        foreach ($options as $key=>$val) {
            $this->$key = $val;
        }
        
        $this->routes = $this->getDefaultRoutes();
    }
    
    public function route($requestURI,  $options = array())
    {
        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen($_SERVER['QUERY_STRING']) - 1);
        }
        
        // Trim the base part of the URL
        $requestURI = substr($requestURI, strlen(parse_url($this->baseURL, PHP_URL_PATH)));
        
        if (isset($options['view'], $routes[$options['view']])) {
            $options['model'] = $routes[$options['view']];
            return $options;
        }

        if (empty($requestURI)) {
            return $options;
        }

        foreach ($this->routes as $route_exp=>$model) {
            if ($route_exp[0] == '/' && preg_match($route_exp, $requestURI, $matches)) {
                $options += $matches;
                $options['model'] = $model;
                return $options;
            }
        }
        
        return $options;
    }
    
    public function setRoutes($newRoutes)
    {
        $this->routes = $newRoutes;
    }
    
    public function getRoutes()
    {
        return $this->routes;
    }
    
    public function getDefaultRoutes()
    {
        if (!self::$cacheRoutes) {
            return $this->compileRoutes();
        }
        
        if (file_exists($this->getCachePath())) {
            $cache = file_get_contents($this->getCachePath());
            return unserialize($cache);
        }
        
        return $this->cacheRoutes();
    }
    
    public function cacheRoutes()
    {
        $routes = $this->compileRoutes();
        
        file_put_contents($this->getCachePath(), serialize($routes));
        
        return $routes;
    }
    
    public function getCachePath()
    {
        return sys_get_temp_dir() . "/" . __CLASS__ . "_Cache.php";
    }
    
    public function compileRoutes()
    {
        $routes = array();
        
        //Directory itterator
        $directory = new DirectoryIterator($this->classDir);
        
        //Compile all the routes.
        foreach ($directory as $file) {
            if ($file->getType() == 'dir' && !$file->isDot()) {
                $class = $this->classPrefix . $file->getFileName() . "_Routes";
                if (class_exists($class)) {
                    $routes += call_user_func($class . "::getRoutes");
                }
            }
        }
        
        return $routes;
    }
}