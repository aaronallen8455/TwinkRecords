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
    const IMAGE = 'image';
    const THUMBNAIL = 'thumbnail';
    const TITLE = 'title';
    const SORT_ORDER = 'sort_order';
    const IS_ACTIVE = 'is_active';
    
    protected $photo_id;
    protected $image;
    protected $thumbnail;
    protected $title;
    protected $sort_order;
    protected $is_active;

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [
            self::ID => $this->photo_id,
            self::IMAGE => $this->image,
            self::THUMBNAIL => $this->thumbnail,
            self::TITLE => $this->title,
            self::SORT_ORDER => $this->sort_order,
            self::IS_ACTIVE => $this->is_active
        ];
    }

    /**
     * Get photo html
     * 
     * @return string
     */
    public function toHtml()
    {
        $image = 'http://' . BASE_URL . 'web/images/photos/' . $this->image;
        // escape 's
        $title = str_replace("'", '&#39;', $this->title);
        return "<img src='$image' class='photo' alt='$title'>";
    }

    /**
     * Get thumbnail html
     *
     * @return string
     */
    public function toThumbnailHtml()
    {
        $image = 'http://' . BASE_URL . 'web/images/photos/' . $this->thumbnail;
        // escape 's
        $title = str_replace("'", '&#39;', $this->title);
        return "<img src='$image' class='photo-thumbnail' alt='$title'>";
    }

    /**
     * Delete image files
     *
     * @return bool
     */
    public function delete()
    {
        unlink('http://' . BASE_URL . 'web/images/photos/' . $this->image);
        unlink('http://' . BASE_URL . 'web/images/photos/' . $this->thumbnail);

        return parent::delete();
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
        $errors['thumbnail'] = false;
        if (!$this->image) {
            $errors['image'] = !isset($_FILES['image']);
        }

        //validate image and get thumbnail
        if (!in_array(true, $errors)) {

            if (is_uploaded_file($_FILES['image']['tmp_name']) && ($_FILES['image']['error'] === UPLOAD_ERR_OK)) {
                if (!empty($_FILES['image']['name']) && isset($_SESSION['image'])) {
                    unlink($_SESSION['image']);
                    unlink($_SESSION['thumbnail']);
                }

                $file = $_FILES['image'];
                //check file size
                $size = round($file['size']/1024);
                if ($size > 3000) {
                    $errors['image'] = 'The uploaded file was too large.';
                    unlink($file['tmp_name']);
                }else{
                    //validate file type
                    //$allowed_mime = array('image/gif', 'image/pjep', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
                    $allowed_extensions = array('.jpg', '.gif', '.png', 'jpeg');
                    //$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
                    //$file_type = finfo_file($fileinfo, $file['tmp_name']);
                    //finfo_close($fileinfo);
                    $file_ext = substr($file['name'], -4);
                    if (/*!in_array($file_type, $allowed_mime) || */!in_array($file_ext, $allowed_extensions)) {
                        $errors['image'] = 'The uploaded file was not of the proper type.';
                        unlink($file['tmp_name']);
                    }
                }

                //if no errors, we process the image
                if (empty($errors['image'])) {
                    //if file dimensions are too large, resize them.
                    $maxHeight = 900;
                    $maxWidth = 1600;

                    $thumbMaxHeight = 90;
                    $thumbMaxWidth = 160;

                    //user proper image create function for each file type
                    switch ($file_ext) {
                        case '.jpg' :
                        case 'jpeg' :
                            $src = imagecreatefromjpeg($file['tmp_name']);
                            break;
                        case '.gif' :
                            $src = imagecreatefromgif($file['tmp_name']);
                            break;
                        case '.png' :
                            $src = imagecreatefrompng($file['tmp_name']);
                    }
                    $thumbHeight = $newHeight = $height = imagesy($src);
                    $thumbWidth = $newWidth = $width = imagesx($src);
                    
                    //check size
                    if ($height > $maxHeight) {
                        $newHeight = $maxHeight;
                        $newWidth = $width * ($newHeight/$height);
                    }
                    if ($newWidth > $maxWidth) {
                        $newHeight = $newHeight * ($maxWidth/$newWidth);
                        $newWidth = $maxWidth;
                    }
                    if ($height > $thumbMaxHeight) {
                        $thumbHeight = $thumbMaxHeight;
                        $thumbWidth = $width * ($thumbHeight/$height);
                    }
                    if ($thumbWidth > $thumbMaxWidth) {
                        $thumbHeight = $thumbHeight * ($thumbMaxWidth/$thumbWidth);
                        $thumbWidth = $thumbMaxWidth;
                    }

                    //make resized image
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($resized, $src, 0,0,0,0, $newWidth, $newHeight, $width, $height);
                    //make thumbnail
                    $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
                    imagecopyresampled($thumbnail, $src, 0,0,0,0, $thumbWidth, $thumbHeight, $width, $height);
                    //create path
                    $imageName = sha1($file['name'] . uniqid('', true)) . '.jpg';
                    $thumbnailName = sha1($file['name'] . uniqid('', true)) . '.jpg';
                    $imagePath = '../web/images/photos/' . $imageName;
                    $thumbnailPath = '../web/images/photos/' . $thumbnailName;
                    imagejpeg($resized, $imagePath);
                    imagejpeg($thumbnail, $thumbnailPath);
                    unlink($file['tmp_name']);
                    $_SESSION['image'] = $imageName;
                    $_SESSION['thumbnail'] = $thumbnailName;
                }
            }else if (!isset($_SESSION['image']) && !isset($_POST['id'])){
                //image selector was left blank for new image
                $errors['image'] = true;
            }
            if (isset($_SESSION['image']) && !in_array(true, $errors)) {
                $data['image'] = $_SESSION['image'];
                $data['thumbnail'] = $_SESSION['thumbnail'];
                unset($_SESSION['image']);
                unset($_SESSION['thumbnail']);
            }
        }

        return parent::prepareData($data, $errors);
    }
}