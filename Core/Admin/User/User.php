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
        if ($stmt = $db->query("SELECT pass FROM users WHERE `name`='$name'")) {
            $hash = $stmt->fetch_row();
            if (password_verify($password, $hash[0])) {
                //user logged in
                $_SESSION['logged_in'] = true;
                return true;
            }
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