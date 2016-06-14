<?php
    // auto load classes
    spl_autoload_register(function ($class_name) {
        include '../' . $class_name . '.php';
    });

    $core = \Core\Core::getInstance();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <?php $cssFile = 'http://' . BASE_URL . 'web/css/styles.css' ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $cssFile ?>" media="all" />
</head>
<body class="admin-body">
<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 5:17 PM
 */



$user = new \Core\Admin\User\User();

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pass']) && isset($_POST['name'])) {
    if (!$user->logIn($_POST['name'], $_POST['pass'])) {
        $loginError = 'Incorrect name or password.';
    }
}

//display admin interface or login form
if ($user->isLoggedIn()) {
    $core->loadTemplate('adminpage');
}else{
    // show login form
    $core->loadTemplate('adminlogin');
}

?>
</body>
</html>
