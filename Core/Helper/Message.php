<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/14/2016
 * Time: 5:15 PM
 */

namespace Core\Helper;


class Message
{
    public static function success($str)
    {
        echo '<div class="message-success"><span>' . $str . '</span></div>';
    }
    
    public static function error($str)
    {
        echo '<div class="message-error"><span>' . $str . '</span></div>';
    }
}