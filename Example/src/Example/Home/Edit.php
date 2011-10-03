<?php
class Example_Home_Edit
{
    public $id;
    
    function __construct(array $options = array())
    {
        if (!isset($options['id']) || empty($options['id'])) {
            throw new Exception("No id was set.", 400);
        }
        
        $this->id = $options['id'];
    }
    
    function speak()
    {
        return "I am in " . __CLASS__ . " and my ID is: " . $this->id;
    }
}