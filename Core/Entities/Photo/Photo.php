<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 4:39 AM
 */

namespace Core\Entities\Photo;


use Core\Entities\AbstractEntity;
use Core\Entities\EntityInterface;

class Photo extends AbstractEntity implements EntityInterface
{
    //Field names
    const ID = 'photo_id';
    const TABLE_NAME = 'photos';
    const URL = 'url';
    const THUMBNAIL = 'thumbnail';
    const TITLE = 'title';
    
    protected $photo_id;
    protected $url;
    protected $thumbnail;
    protected $title;

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [
            self::ID => $this->photo_id,
            self::URL => $this->url,
            self::THUMBNAIL => $this->thumbnail,
            self::TITLE => $this->title
        ];
    }

    /**
     * Get photo html
     * 
     * @return string
     */
    public function toHtml()
    {
        $url = BASE_URL . 'images/' . $this->url;
        return "<img src='$url' class='photo' alt='{$this->title}'>";
    }

    /**
     * Get thumbnail html
     *
     * @return string
     */
    public function toThumbnailHtml()
    {
        $url = BASE_URL . 'images/' . $this->thumbnail;
        return "<img src='$url' class='photo-thumbnail' alt='{$this->title}'>";
    }
}