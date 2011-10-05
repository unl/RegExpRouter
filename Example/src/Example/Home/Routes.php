<?php
namespace Example\Home;

class Routes extends \RegExpRouter\RoutesInterface
{
    public static function getGetRoutes()
    {
        return array('/^home$/i' => 'Example\Home\View',
                     '/^$/i' => 'Example\Home\View', //Match to an empty string, thus this is now the default home page.
                    );
    }
    
    public static function getPostRoutes()
    {
        return array('/^home\/((?<id>[\d]+)\/)?edit$/i' => 'Example\Home\Edit');
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