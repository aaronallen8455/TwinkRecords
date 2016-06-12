<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 1:12 AM
 */

namespace Core\DB;

use Core\Entities\Page\Page;

class DB
{
    /** @var DB $instance */
    static private $instance;

    /** @var  \mysqli $mysqli */
    protected $mysqli;

    // Private methods cannot be called
    /**
     * DB constructor.
     */
    private function __construct()
    {
        $this->mysqli = new \mysqli(HOST, USERNAME, PASSWD, DBNAME);
    }
    
    private function __clone() {}

    /**
     * Return or instantiate the connection object.
     *
     * @return DB
     */
    public static function getConnection()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    }
    
    // Methods
    
    /**
     * Run query
     * 
     * @param string $sql
     * @return bool|\mysqli_result
     */
    public function query($sql)
    {
        return $this->mysqli->query($sql);
    }

    /**
     * Get a page object from the title
     * 
     * @param $title
     * @return $this|null
     */
    public function getPage($title)
    {
        if (preg_match('/^[\w\d\-_]+$/', $title)) {
            $sql = "SELECT * FROM pages WHERE `title`=$title";
            $result = $this->mysqli->query($sql)->fetch_assoc();
            $page = new Page();
            return $page->setData($result);
        }
        return null;
    }

    /**
     * Close the connection
     */
    public function __destruct()
    {
        $this->mysqli->close();
    }
}