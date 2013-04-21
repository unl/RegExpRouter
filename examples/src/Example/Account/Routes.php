<?php
namespace Example\Account;

class Routes extends \RegExpRouter\RoutesInterface
{
    public function getGetRoutes()
    {
        return array('/^account$/i' => 'View'); //'View' refers to the 'View' class for THIS model.
    }
    
    public function getPostRoutes()
    {
        return array();
    }
    
    public function getDeleteRoutes()
    {
        return array();
    }
    
    public function getPutRoutes()
    {
        return array();
    }
}