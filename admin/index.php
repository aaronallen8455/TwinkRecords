<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 5:17 PM
 */

    // auto load classes
    spl_autoload_register(function ($class_name) {
        $class_name = str_replace('\\', '/', $class_name);
        include '../' . $class_name . '.php';
    });

    $core = \Core\Core::getInstance();

    $user = new \Core\Admin\User\User();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Area</title>
    <?php $cssFile = 'http://' . BASE_URL . 'web/css/styles.css' ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $cssFile ?>" media="all" />
</head>
<body class="admin-body">
<div class="admin-content">
<?php

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pass']) && isset($_POST['name'])) {
    if (!$user->logIn($_POST['name'], $_POST['pass'])) {
        $loginError = 'Incorrect name or password.';
    }
}

//display admin interface or login form
if ($user->isLoggedIn()) {
    //route actions
    if (isset($_GET['action'])) {

        // get entity type
        if (isset($_POST['type'])) {
            $type = $_POST['type'];

            // loading existing entity?
            if (isset($_POST['id'])) {
                switch ($type) {
                    case 'event':
                        $entity = new \Core\Entities\Event\Event();
                        break;
                    case 'page':
                        $entity = new \Core\Entities\Page\Page();
                        break;
                    case 'photo':
                        $entity = new \Core\Entities\Photo\Photo();
                        break;
                    case 'default':
                        exit('Invalid type specified.');
                }
                
                if ($_GET['action'] !== 'save')
                    $entity->load($_POST['id']);
            }
        }else exit('No type specified.');
        
        switch ($_GET['action']) {
            case 'edit':
                $core->loadTemplate('admin/edit');
                break;
            case 'delete':
                if (isset($entity) && $entity->getId()) {
                    // confirmed delete?
                    if (isset($_POST['confirm'])) {
                        if ($entity->delete()) {
                            \Core\Helper\Message::success("You deleted the $type.");
                            $core->loadTemplate('admin/adminpage');
                        }else{
                            \Core\Helper\Message::error("An error occurred while attempting to delete the $type.");
                            $core->loadTemplate('admin/adminpage');
                        }
                    }else{
                        //confirm delete
                        $core->loadTemplate('admin/delete');
                    }
                }else{
                    \Core\Helper\Message::error('Specified entity does not exist.');
                    $core->loadTemplate('admin/adminpage');
                }
                break;
            case 'save':
                if (isset($entity)) {
                    $errors = [];
                    $data = $entity->prepareData($_POST, $errors);
                    if (!in_array(true, $errors)) {
                        $entity->setData($data);
                        if ($entity->save()) {
                            \Core\Helper\Message::success("You saved the $type.");
                            $core->loadTemplate('admin/adminpage');
                        }else{
                            \Core\Helper\Message::error("An error occurred while attempting to save the $type.");
                            $core->loadTemplate('admin/adminpage');
                        }
                    }
                }else{
                    \Core\Helper\Message::error('Specified entity does not exist.');
                    $core->loadTemplate('admin/adminpage');
                }
        }
    }else{
        // show admin interface
        $core->loadTemplate('admin/adminpage');
    }
}else{
    // show login form
    $core->loadTemplate('admin/adminlogin');
}

?>
</div>
</body>
</html>
