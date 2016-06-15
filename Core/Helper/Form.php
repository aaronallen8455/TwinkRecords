<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/14/2016
 * Time: 5:57 PM
 */

namespace Core\Helper;


use Core\Entities\AbstractEntity;

class Form
{
    /** @var array $errors */
    protected $errors;

    /** @var  AbstractEntity $entity */
    protected $entity;

    /**
     * Form constructor.
     * @param AbstractEntity|null $entity
     * @param array $errors
     */
    public function __construct(AbstractEntity $entity = null, array $errors)
    {
        $this->entity = $entity;
        $this->errors = $errors;
    }

    /**
     * Create a text input
     *
     * @param string $name
     * @param string $label
     * @return string
     */
    public function textInput($name, $label)
    {
        $value = '';
        if (!is_null($this->entity)) {
            $value = $this->entity->getProperty($name);
        }

        $html = '';
        $html .= '<div class="form-input' . (($this->errors && $this->errors[$name])?' form-error':'') . '">';
        $html .= "<label for='$name'>$label</label>";
        $html .= "<input id='$name' name='$name' type='text' value='$value' />";
        $html .= '</div>';

        return $html;
    }

    /**
     * Create Yes/No select
     *
     * @param string $name
     * @param string $label
     * @return string
     */
    public function yesNoInput($name, $label)
    {
        $isNo = false;
        if (!is_null($this->entity)) {
            $isNo = $this->entity->getProperty($name) == 0;
        }

        $html = '';
        $html .= '<div class="form-input' . (($this->errors && $this->errors[$name])?' form-error':'') . '">';
        $html .= "<label for='$name'>$label</label>";
        $html .= "<select id='$name' name='$name'>";
        $html .= '<option value="1">Yes</option>';
        $html .= '<option value="0"' . ($isNo?' selected':'') . '>No</option>';
        $html .= '</select>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Create image input
     *
     * @param string $name
     * @param string $label
     * @return string
     */
    public function imageInput($name, $label)
    {
        $currentImage = false;
        if (!is_null($this->entity)) {
            //get thumbnail image if an image was loaded previously
            $currentImage = $this->entity->toThumbnailHtml();
        }

        $html = '';
        $html .= '<div class="form-input' . (($this->errors && $this->errors[$name])?' form-error':'') . '">';
        $html .= "<label for='$name'>$label</label>";
        $html .= "<input id='$name' name='$name' type='file' />";
        if ($currentImage) {
            $html .= "<span>Currently Selected: </span>" . $currentImage;
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * Create text area input
     * 
     * @param string $name
     * @param string $label
     * @return string
     */
    public function textArea($name, $label)
    {
        $value = '';
        if (!is_null($this->entity)) {
            $value = $this->entity->getProperty($name);
        }

        $html = '';
        $html .= '<div class="form-input' . (($this->errors && $this->errors[$name])?' form-error':'') . '">';
        $html .= "<label for='$name'>$label</label>";
        $html .= "<textarea name='$name' id='$name' class='admin-form-textarea'>$value</textarea>";
        $html .= '</div>';
        
        return $html;
    }

    public function dateTimeInput($name, $label)
    {
        $html = '';
        $html .= '<div class="form-input' . (($this->errors && $this->errors[$name])?' form-error':'') . '">';
        $html .= "<label for='$name'>$label</label>";
        $html .= "<span>";
        $html .= "<select name='month'>";
        for ($i=0; $i<12; $i++) {
            //print each month
            $date = new \DateTime();
            $date->add(new \DateInterval('P'.$i.'M'));
            $monthNum = $date->format('m');
            $monthName = $date->format('F');
            $html .= "<option value='$monthNum'>$monthName</option>";
        }
        $html .= "</select>";
        $html .= "<select name='day'></select>"; //make options in JS
        $html .= "<select name='year'>";
        for ($i=0; $i<5; $i++) {
            $year = (int)date('Y') + $i;
            $html .= "<option value='$year'>$year</option>";
        }
        $html .= '</select>';
        $html .= 'Time: <input type="text" name="time" placeholder="ex. 9:30pm"/>';

        return $html;
    }
}