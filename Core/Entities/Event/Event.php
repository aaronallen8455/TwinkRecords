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

    /**
     * Form the html
     * 
     * @return string
     */
    public function toHtml()
    {
        $html = '<h2 class="event-title">' . $this->title . '</h2>';
        $html .= '<span class="event-date">' . date('g:ia', strtotime($this->datetime)) . ' at ' . ucwords($this->location) . '</span>';
        $html .= '<div class="event-content-wrapper">';
        $html .= '<p class="event-content">' . $this->content . '</p>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Prepare input data
     *
     * @param array $data
     * @param array $errors
     * @return array
     */
    public function prepareData(array $data, array &$errors)
    {
        $errors = $this->checkDataCompletion($data, $errors);
        //parse the datetime
        $errors['datetime'] = false;
        if (empty($data['time'])) $errors['datetime'] = true;
        else {
            //test the date
            if (preg_match_all('/^(\d{1,2}):(\d{2})(am|pm)$/i', $data['time'], $time, PREG_PATTERN_ORDER)) {
                //validate time
                if ((int)$time[1][0] <= 12 && (int)$time[1][0] > 0 && (int)$time[2][0] <= 59) {
                    //correct the hour value to 24 hour clock
                    if ($time[1][0] === '12') $time[1][0] = 0;
                    if (!strcasecmp($time[3][0], 'pm')) {
                        $time[1][0] += 12;
                    }
                    $date = new \DateTime();
                    $date->setDate($data['year'], $data['month'], $data['day']);
                    $date->setTime($time[1][0], $time[2][0], 0);
                    $data['datetime'] = $date->format('Y-m-d H:i:s');
                }else $errors['datetime'] = true;
            }else $errors['datetime'] = true;
        }
        // some formatting
        if (!in_array(true, $errors)) {
            $data['title'] = trim($data['title']);
            $data['location'] = trim($data['location']);
        }

        return parent::prepareData($data, $errors);
    }
}