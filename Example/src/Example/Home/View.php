<?php
class Example_Home_View
{
    function __construct(array $options = array())
    {
        
    }
    
    function speak()
    {
        return "I am in " . __CLASS__;
    }
}