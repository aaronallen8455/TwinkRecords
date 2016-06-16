<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 2:17 PM
 */

namespace Core;


use Core\DB\DB;

class Core
{
    static private $instance;

    private function __construct()
    {
        // is local environment?
        $host = substr($_SERVER['HTTP_HOST'], 0, 5);
        if (in_array($host, array('local', '127.0', '192.1'))) { //determine if host is local or on the server.
            //$this->local = true;
            define('IS_LOCAL', true);
        }else{
            //$this->local = false;
            define('IS_LOCAL', false);
        }

        //errors are emailed here:
        define('CONTACT_EMAIL', 'aaronallen8455@gmail.com');

        if(IS_LOCAL) {
            define('BASE_URI', 'file:///B:/Applications/xampp/htdocs/twink');
            define('BASE_URL', 'localhost/twink/');
            define('DB_DBNAME', 'twink');
            define('DB_USERNAME', 'root');
            define('DB_PASSWD', '');
        }else{//live
            define('BASE_URI', '/home/twinkrec/www');
            define('BASE_URL', 'twinkrecords.com/');
            define('DB_DBNAME', '******');
            define('DB_USERNAME', '******');
            define('DB_PASSWD', '******');
        }
        define('DB_HOST', 'localhost');

        //use the error handler
        set_error_handler(['Core\Core', 'errorHandler']);
    }

    /**
     * Custom error handler
     * 
     * @param $e_number
     * @param $e_message
     * @param $e_file
     * @param $e_line
     * @param $e_vars
     * @return bool
     */
    public function errorHandler($e_number, $e_message, $e_file, $e_line, $e_vars)
    {
        //build the error message
        $message = "An error occured in script '$e_file' on line $e_line:\n$e_message\n";
        //add the backtrace
        $message .= "<pre>" . print_r(debug_backtrace(), 1) . "</pre>\n";
        //show message if not live
        if (IS_LOCAL) {
            echo '<div class="error">' . nl2br($message) . '</div>';
        }else{
            //send the error in an email
            error_log($message, 1, CONTACT_EMAIL, 'From:admin@twinkrecords.com');
            //only print message in browser if error isn't a notice
            if ($e_number != E_NOTICE) {
                echo '<div class="error">A system error occured. We apologize for the inconvenience.</div>';
            }
        }
        return true; //so that php doesn't try to handle the error too.
    }

    private function __clone() {}

    /**
     * Get singleton
     * 
     * @return Core
     */
    static public function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Core();
        }
        return self::$instance;
    }

    /**
     * Load the header
     * 
     * @param string $pageTitle
     */
    public function header($pageTitle = '')
    {
        // Define the page title.
        if ($pageTitle) {
            $pageTitle = htmlentities($pageTitle) . ' - Twink Records';
        }else{
            $pageTitle = 'Twink Records';
        }
        
        // Get the navigation links
        $db = DB::getConnection();
        $navLinks = $db->getNavLinks();
        
        include BASE_URI . '/templates/header.phtml';
    }

    /**
     * Load the footer
     */
    public function footer()
    {
        include BASE_URI . '/templates/footer.phtml';
    }

    /**
     * Render the page
     * 
     * @param $pageKey
     */
    public function loadPage($pageKey)
    {
        $db = DB::getConnection();

        if ($page = $db->getPage($pageKey)) {
            $this->header($page->getTitle());
            // pages with special content handled here
            if (in_array($pageKey, ['calendar', 'photos'])) {
                echo $page->toHtml();
                $this->loadTemplate($pageKey);
            }else{
                // Basic content page: get the page from DB
                // don't display content title on front page
                echo $page->toHtml($pageKey !== 'front');
            }
        }else{
            // 404 error
            $page = $db->getPage('404');
            $this->header($page->getTitle());
            echo $page->toHtml();
        }

        $this->footer();
    }

    /**
     * Load a template file by name
     * 
     * @param $name
     */
    public function loadTemplate($name)
    {
        if (file_exists(BASE_URI . "/templates/$name.phtml")) {}
            include BASE_URI . "/templates/$name.phtml";
    }
}