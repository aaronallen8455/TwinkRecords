<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 1:50 AM
 */

namespace Core\Entities\Page;


use Core\DB\DB;
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
    const IS_ACTIVE = 'is_active';
    const SHOW_IN_MENU = 'show_in_menu';

    protected $page_id;
    protected $content;
    protected $title;
    protected $url_key;
    protected $sort_order;
    protected $is_active;
    protected $show_in_menu;

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
            self::SORT_ORDER => $this->sort_order,
            self::IS_ACTIVE => $this->is_active,
            self::SHOW_IN_MENU => $this->show_in_menu
        ];
    }

    /**
     * Prepare data and errors
     *
     * @param array $data
     * @param array $errors
     * @return array
     */
    public function prepareData(array $data, array &$errors)
    {
        $errors = $this->checkDataCompletion($data, $errors);

        if (!in_array(true, $errors)) {
            // check that url_key is unique
            $db = DB::getConnection();
            $page = $db->getPage($data['url_key']);
            if (!is_null($page) && $this->getId() !== $page->getId()) {
                $errors['url_key'] = true;
            }

            $data['title'] = trim($data['title']);
            $data['url_key'] = str_replace(' ', '-', $data['url_key']);
            $data['url_key'] = preg_replace('/[^\w\d\-]/', '', $data['url_key']);
        }
        
        return parent::prepareData($data, $errors);
    }
}