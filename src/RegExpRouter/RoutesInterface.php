<?php
/**
 * This class is a routes interface.  All Routes class must
 * implement this interface to work properly.
 * 
 * @author mfairchild365
 *
 */
namespace RegExpRouter;
abstract class RoutesInterface
{
    /**
     * All of the Post POST for this model.
     * @return array
     */
    abstract public function getPostRoutes();
    
    /**
     * All of the GET Routes for this model.
     * @return array
     */
    abstract public function getGetRoutes();
    
    /**
     * All of the DELETE Routes for this model.
     * @return array
     */
    abstract public function getDeleteRoutes();
    
    /**
     * All of the PUT Routes for this model.
     * @return array
     */
    abstract public function getPutRoutes();
    
    /**
     * Gathers all of the Routes for this object.
     * It then adds the called class's parent's namespace to all of the routes.
     * Finally it returns the routs with the added namespace.
     * 
     * @return array $routes
     */
    public static function getRoutes()
    {
        $class     = get_called_class();
        $object    = new $class();
        $routes    = array();
        $namespace = substr($class, 0, strlen($class)-6);

        $routes += $object->getPostRoutes();
        $routes += $object->getGetRoutes();
        $routes += $object->getDeleteRoutes();
        $routes += $object->getPutRoutes();

        return $object->addNamesapces($namespace, $routes);
    }
    
    /**
     * Adds a namespace to the routes's model class.
     * 
     * @param string $nameSpace
     * @param array $routes
     * 
     * @return array $newRoutes
     */
    protected function addNamesapces($nameSpace, array $routes) {
        $newRoutes = array();
        
        foreach ($routes as $regex=>$route) {
            $route = $nameSpace.$route;
            $newRoutes[$regex] = $route;
        }
        
        return $newRoutes;
    }
}