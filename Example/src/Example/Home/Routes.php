<?php
class Example_Home_Routes extends RegExpRouter_RoutesInterface
{
    public static function getGetRoutes()
    {
        return array('/^home$/i' => 'Example_Home_View',
                     '/^$/i' => 'Example_Home_View', //Match to an empty string, thus this is now the default home page.
                    );
    }
    
    public static function getPostRoutes()
    {
        return array('/^home\/((?<id>[\d]+)\/)?edit$/i' => 'Example_Home_Edit');
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