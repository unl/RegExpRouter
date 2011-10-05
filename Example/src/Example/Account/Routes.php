<?php
namespace Example\Account;

class Routes extends \RegExpRouter\RoutesInterface
{
    public static function getGetRoutes()
    {
        return array('/^account$/i' => 'Example\Account\View');
    }
    
    public static function getPostRoutes()
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
}