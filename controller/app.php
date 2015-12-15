<?php
/**
 * Created by PhpStorm.
 * User: tristan
 * Date: 10/12/2015
 * Time: 11:56
 */

namespace AppController;

require_once __DIR__ . '/../model/pictures.php';

function perform()
{
    if (!isset($_FILES['picture']['error']) ||
        is_array($_FILES['picture']['error']) || !isset($_POST['filter'])
    ) {
        return 'Something went wrong with your images';
    }

    switch ($_FILES['picture']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return 'Missing picture';
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'Picture too big';
        default:
            return 'Something went wrong with your picture';
    }

    function get_mime($url) {
        $image_type = exif_imagetype($url);
        return image_type_to_mime_type($image_type);
    }

    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $type = $finfo->file($_FILES['picture']['tmp_name']);
    $ext = null;
    if ($type === 'image/png') {
        $ext = 'png';
    } else if ($type === 'image/jpeg') {
        $ext = 'jpeg';
    } else {
        return 'Wrong type file';
    }

    $picturefunction = 'imagecreatefrom' . $ext;

    $picture = $picturefunction($_FILES['picture']['tmp_name']);
    $resized_picture = imagecreatetruecolor(320, 240);
    list($width, $height) = getimagesize($_FILES['picture']['tmp_name']);
    imagealphablending($resized_picture, true);
    imagesavealpha($resized_picture, true);
    imagecopyresized($resized_picture, $picture, 0, 0, 0, 0, 320, 240, $width, $height);

    $filter = imagecreatefrompng($_POST['filter']);
    $resized_filter = imagecreatetruecolor(320, 240);
    list($width, $height) = getimagesize($_POST['filter']);
    imagealphablending($resized_filter, false);
    imagesavealpha($resized_filter, true);
    imagecopyresized($resized_filter, $filter, 0, 0, 0, 0, 320, 240, $width, $height);

    imagecopy($picture, $resized_filter, 0, 0, 0, 0, 320, 240);

    ob_start();
    imagepng($picture);
    $img = ob_get_contents();
    ob_end_clean();

    imagedestroy($picture);
    imagedestroy($filter);
    imagedestroy($resized_filter);
    imagedestroy($resized_picture);

    \PictureModel\add($img);
    return null;
}

function display_user_pictures() {
    foreach (\PictureModel\get_all_from_user() as $query) {
        echo '<img src="data:image/jpeg;base64,' . base64_encode( $query['data'] ). '" />';
    }
}