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

    /**
     * DB constructor.
     */
    private function __construct()
    {
        $this->mysqli = new \mysqli(DB_HOST, DB_USERNAME, DB_PASSWD, DB_DBNAME);
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
        $sql = $this->mysqli->real_escape_string($sql);
        return $this->mysqli->query($sql);
    }

    /**
     * Get a page object from the title
     * 
     * @param $key
     * @return Page|null
     */
    public function getPage($key)
    {
        if (preg_match('/^[\w\d\-_]+$/', $key)) {
            $sql = "SELECT * FROM pages WHERE `url_key`=$key";
            $result = $this->mysqli->query($sql)->fetch_assoc();
            $page = new Page();
            return $page->setData($result);
        }
        return null;
    }

    /**
     * Get navigation links
     * 
     * @return array
     */
    public function getNavLinks()
    {
        $sql = 'SELECT title, url_key FROM pages ORDER BY sort_order ASC';
        $navLinks = [];
        $stmt = $this->query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $navLinks[$row['title']] = $row['url_key'];
        }
        return $navLinks;
    }

    /**
     * Close the connection
     */
    public function __destruct()
    {
        $this->mysqli->close();
    }
}