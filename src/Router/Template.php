<?php
class Router_Template
{
    public static function getPostRoutes()
    {
        return array();
    }
    
    public static function getGetRoutes()
    {
        return array();
    }
    
    public static function getDeleteRoutes()
    {
        return array();
    }
    
    public static function getPutRoutes()
    {
        return array();
    }
    
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