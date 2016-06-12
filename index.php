<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 12:18 AM
 */

// auto load classes
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

require 'includes/config.inc.php';

//get the requested page name. default to front page.
$pageTitle = isset($_GET['page'])?$_GET['page']:'front';

if (in_array($pageTitle, ['calendar', 'photos'])) {
    switch ($pageTitle) {
        case 'calendar' :
            break;
        case 'photos' :
            break;
    }
}else{
    //get the page from the DB
    $db = \Core\DB\DB::getConnection();
    if ($page = $db->getPage($pageTitle)) {

    }
}
