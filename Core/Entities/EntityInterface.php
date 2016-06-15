<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 2:30 AM
 */

namespace Core\Entities;


interface EntityInterface
{
    public function getData();
    
    public function setData(array $array);
    
    public function save();
    
    public function delete();
}