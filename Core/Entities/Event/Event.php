<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 4:30 AM
 */

namespace Core\Entities\Event;


use Core\Entities\AbstractEntity;
use Core\Entities\EntityInterface;

class Event extends AbstractEntity implements EntityInterface
{
    //Field names
    const ID = 'event_id';
    const CONTENT = 'content';
    const DATETIME = 'datetime';
    const TITLE = 'title';
    const LOCATION = 'location';
    const TABLE_NAME = 'events';
    
    protected $event_id;
    protected $content;
    protected $datetime;
    protected $title;
    protected $location;

    /**
     * Get data
     * 
     * @return array
     */
    public function getData()
    {
        return [
            self::ID => $this->event_id,
            self::CONTENT => $this->content,
            self::DATETIME => $this->datetime,
            self::LOCATION => $this->location,
            self::TITLE => $this->title
        ];
    }
}