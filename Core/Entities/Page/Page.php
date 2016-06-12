<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 1:50 AM
 */

namespace Core\Entities\Page;


use Core\Entities\AbstractEntity;
use Core\Entities\EntityInterface;

class Page extends AbstractEntity implements EntityInterface
{
    // Field names
    const ID = 'page_id';
    const CONTENT = 'content';
    const TITLE = 'title';
    const URL_KEY = 'url_key';
    const TABLE_NAME = 'pages';
    const SORT_ORDER = 'sort_order';

    protected $page_id;
    protected $content;
    protected $title;
    protected $url_key;
    protected $sort_order;

    /**
     * Get page html
     * 
     * @param bool $showTitle
     * @return string
     */
    public function toHtml($showTitle = true)
    {
        $html = '';
        if ($showTitle) $html .= '<h2 class="page-title">' . $this->title . '</h2>';
        $html .= '<p class="page-content">' . $this->content . '</p>';
        
        return $html;
    }

    /**
     * Get data
     * 
     * @return array
     */
    public function getData()
    {
        return [
            self::ID => $this->page_id,
            self::CONTENT => $this->content,
            self::TITLE => $this->title,
            self::URL_KEY => $this->url_key,
            self::SORT_ORDER => $this->sort_order
        ];
    }
}