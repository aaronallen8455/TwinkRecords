<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 1:12 AM
 */

namespace Core\DB;

use Core\Entities\Event\Event;
use Core\Entities\Page\Page;
use Core\Entities\Photo\Photo;

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
            $sql = "SELECT * FROM pages WHERE `url_key`='$key' AND `is_active`=1";
            $result = $this->mysqli->query($sql)->fetch_assoc();
            $page = new Page();
            if (!is_null($result))
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
        $sql = 'SELECT title, url_key FROM pages WHERE is_active=1 AND show_in_menu=1 ORDER BY sort_order ASC';
        $navLinks = [];
        $stmt = $this->query($sql);
        while ($row = $stmt->fetch_assoc()) {
            if ($row['url_key'] === 'front')
                $navLinks[$row['title']] = '';
            else
                $navLinks[$row['title']] = $row['url_key'] . '/';
        }
        return $navLinks;
    }

    /**
     * Get an array of Events indexed by datetime
     * 
     * @param bool $current
     * @return array
     */
    public function getEvents($current = true)
    {
        $events = [];
        $sql = 'SELECT * FROM events WHERE `datetime` ' . ($current?'>=':'<') . ' NOW() ORDER BY `datetime` ' . ($current?'ASC':'DESC');
        $stmt = $this->query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $event = new Event();
            $event->setData($row);
            $events[$row['datetime']][] = $event;
        }
        return $events;
    }

    /**
     * Get array of all photos
     * 
     * @return array
     */
    public function getPhotos()
    {
        $photos = [];
        $sql = 'SELECT * FROM photos ORDER BY sort_order ASC';
        $stmt = $this->query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $photo = new Photo();
            $photo->setData($row);
            $photos[] = $photo;
        }
        return $photos;
    }

    /**
     * Close the connection
     */
    public function __destruct()
    {
        $this->mysqli->close();
    }
}