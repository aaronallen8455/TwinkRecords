<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/14/2016
 * Time: 5:57 PM
 */

namespace Core\Helper;


class Form
{
    protected $errors;
    protected $entity;
    
    public function __construct($entity, $errors)
    {
        $this->entity = $entity;
        $this->errors = $errors;
    }
    
    public function textInput($name, $label)
    {
        
    }
    
    public function selectInput($name, $label)
    {
        
    }
    
    public function imageInput($name, $label)
    {
        
    }
}