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
    abstract public static function getPostRoutes();
    
    /**
     * All of the GET Routes for this model.
     * @return array
     */
    abstract public static function getGetRoutes();
    
    /**
     * All of the DELETE Routes for this model.
     * @return array
     */
    abstract public static function getDeleteRoutes();
    
    /**
     * All of the PUT Routes for this model.
     * @return array
     */
    abstract public static function getPutRoutes();
    
    /**
     * Gathers all of the Routes for this object and returns them.
     */
    public static function getRoutes()
    {
        $class = get_called_class();
        $routes = array();
        
        $routes += call_user_func($class . "::getPostRoutes");
        $routes += call_user_func($class . "::getGetRoutes");
        $routes += call_user_func($class . "::getDeleteRoutes");
        $routes += call_user_func($class . "::getPutRoutes");
        
        return $routes;
    }
}