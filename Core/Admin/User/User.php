<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/13/2016
 * Time: 11:21 PM
 */

namespace Core\Admin\User;


use Core\DB\DB;

class User
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        //start session if none exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            if (!isset($_SESSION['logged_in']))
                $_SESSION['logged_in'] = false;
        }
    }

    /**
     * Log in
     *
     * @param $name
     * @param $password
     * @return bool
     */
    public function logIn($name, $password)
    {
        $db = DB::getConnection();
        $name = $db->escapeString($name);
        $sql = "SELECT pass FROM users WHERE `name`='$name'";
        if ($stmt = $db->query($sql)) {
            $hash = $stmt->fetch_row();
            $hash = $hash[0];
            //if (password_verify($password, $hash)) {
            if (sha1($password) === $hash) { // GoProHosting sucks
                //user logged in
                $_SESSION['logged_in'] = true;
                return true;
            }
            $stmt->close();
        }
        return false;
    }

    /**
     * Is user logged in
     * 
     * @return bool
     */
    public function isLoggedIn()
    {
        return (session_status() === PHP_SESSION_ACTIVE && $_SESSION['logged_in']);
    }
}