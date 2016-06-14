<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 12:18 AM
 */

// auto load classes
spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    include $class_name . '.php';
});

$core = \Core\Core::getInstance();

//get the requested page name. default to front page.
$pageKey = isset($_GET['page'])?$_GET['page']:'front';

//render the page
$core->loadPage($pageKey);
