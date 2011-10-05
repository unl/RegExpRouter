<?php
class Example_Account_Routes extends RegExpRouter_RoutesInterface
{
    public static function getGetRoutes()
    {
        return array('/^account$/i' => 'Example_Account_View');
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