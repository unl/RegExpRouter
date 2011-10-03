<?php
/**
 * RegExpRouter
 * 
 * This class is used to compile all of the routes for a php application and return
 * routes based on regex and a URI.
 * 
 * @author mfairchild365
 */
class RegExpRouter
{
    //Determins if routes should be cached or not.
    public static $cacheRoutes = false;
    
    //Class prefix for your sysem.  Determins name of Routes class, IE: Mysystem_Module_Routes
    private $classPrefix = "";
    
    //The directory where your source is stored.
    private $classDir = "";
    
    //Array of routes
    private $routes = array();
    
    /**
     * Constructor
     * 
     * @param array $options - array of options. Requires baseURL, classDir and classPrefix be defined.
     * 
     * @throws Exception
     */
    function __construct(array $options = array())
    {
        //Check if the baseURL is set.
        if (!isset($options['baseURL']) || empty($options['baseURL'])) {
            throw new Exception("You must define the baseURL", 500);
        }
        
        //check if the classPrefix is set.
        if (!isset($options['classPrefix']) || empty($options['classPrefix'])) {
            $options['classPrefix'] = "";
        }
        
        //Set all class properties with the passed options.
        foreach ($options as $key=>$val) {
            $this->$key = $val;
        }
        
        //Get the default routes.
        $this->routes = $this->getDefaultRoutes();
    }
    
    /**
     * Routes based on a requestURI and options.
     * 
     * @param string $requestURI
     * @param array $options
     * 
     * @return array $options - with the model defined (if one was found).
     */
    public function route($requestURI, array $options = array())
    {
        //tidy up the requestURI
        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen($_SERVER['QUERY_STRING']) - 1);
        }
        
        // Trim the base part of the URL
        $requestURI = substr($requestURI, strlen(parse_url($this->baseURL, PHP_URL_PATH)));
        
        //For older systems we used 'view' instead of 'model', this allows for backwards compatability.
        if (isset($options['view'], $routes[$options['view']])) {
            $options['model'] = $routes[$options['view']];
            return $options;
        }
        
        //Loop though all of the routes and check to see the current url matches any routes.
        foreach ($this->routes as $route_exp=>$model) {
            if ($route_exp[0] == '/' && preg_match($route_exp, $requestURI, $matches)) {
                $options += $matches;
                $options['model'] = $model;
                return $options;
            }
        }
        
        //No routes were found, don't return a model.
        return $options;
    }
    
    /**
     * Set the routes.
     * 
     * @param array $newRoutes
     */
    public function setRoutes(array $newRoutes)
    {
        $this->routes = $newRoutes;
    }
    
    /**
     * Get the routes
     * 
     * @return array $routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    /**
     * Gets the default routes by using the cache if we are using cached
     * routes or by compiling the routes.
     * 
     * @return array $routes
     */
    public function getDefaultRoutes()
    {
        //if we are not caching routes, just compile them.
        if (!self::$cacheRoutes) {
            return $this->compileRoutes();
        }
        
        //We are caching routes, so check if we have them cached.
        if (file_exists($this->getCachePath())) {
            //We have them cached, so send them back.
            $cache = file_get_contents($this->getCachePath());
            return unserialize($cache);
        }
        
        //cache the routs because they haven't been cached yet.
        return $this->cacheRoutes();
    }
    
    /**
     * Caches the routes.
     * 
     * @return Array $routes
     */
    public function cacheRoutes()
    {
        //Get the routes.
        $routes = $this->compileRoutes();
        
        //Save the routes on the file system.
        file_put_contents($this->getCachePath(), serialize($routes));
        
        return $routes;
    }
    
    /**
     * Generates and returns the cache path for routes.
     * The path is determined by a hash of the class directory name and prefix.
     * 
     * @return string
     */
    public function getCachePath()
    {
        return sys_get_temp_dir() . "/RegExRouterCache_" . md5($this->classDir . $this->classPrefix) . ".php";
    }
    
    /**
     * Compiles the routes by looping though all of the models and getting the routes for each model.
     * 
     * @return array $routes
     */
    public function compileRoutes()
    {
        //Initialize an empty array.
        $routes = array();
        
        //Check if we are going to sift though directories.
        if (empty($this->classDir)) {
            return $routes;
        }
        
        //Directory itterator
        $directory = new DirectoryIterator($this->classDir);
        
        //Compile all the routes.
        foreach ($directory as $file) {
            //Only check diretories.
            if ($file->getType() == 'dir' && !$file->isDot()) {
                //Generate the class name for the routes class.
                $class = $this->classPrefix . $file->getFileName() . "_Routes";
                
                //Check if the class exists, and if it does get its routes.
                if (class_exists($class)) {
                    $routes += call_user_func($class . "::getRoutes");
                }
            }
        }
        
        return $routes;
    }
}