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
    const SORT_ORDER = 'sort_order';
    
    protected $photo_id;
    protected $url;
    protected $thumbnail;
    protected $title;
    protected $sort_order;

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
            self::TITLE => $this->title,
            self::SORT_ORDER => $this->sort_order
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
        // escape 's
        $title = str_replace("'", '&#39;', $this->title);
        return "<img src='$url' class='photo' alt='$title'>";
    }

    /**
     * Get thumbnail html
     *
     * @return string
     */
    public function toThumbnailHtml()
    {
        $url = BASE_URL . 'images/' . $this->thumbnail;
        // escape 's
        $title = str_replace("'", '&#39;', $this->title);
        return "<img src='$url' class='photo-thumbnail' alt='$title'>";
    }
}