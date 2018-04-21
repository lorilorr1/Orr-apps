<?php

//
//Created on Mar 4, 2007 OrPicture.php
//@version php5
//@author Suchart Bunhachirat
//@copyright Copyright &copy; 2007, orr
//To change the template for this generated file go to
//Window - Preferences - PHPeclipse - PHP - Code Templates

class OrPicture extends OrGui {

    private $file_name = null;
    private $image = null;

    function __construct($id = '', $name = null, $idx = null) {
        parent :: __construct($id, $name, $idx);
    }

    function set_image($name, $type = 'jpeg') {
        /* $name = path & name ของไฟล์ */
        //if(!is_file($name))die('Error isnot File ' . $name);
        if (file_exists($name) AND basename($name) != '') {
            switch ($type) {
                case 'jpeg' :
                    $im = imagecreatefromjpeg($name);
                    break;
                case 'gif' :
                    $im = imagecreatefromgif($name);
                    break;
                default :
                    die('Error set_picture ' . $name . ' : ' . $type);
            }
        } else {
            $im = imagecreate(150, 30); /* Create a blank image */
            $bgc = imagecolorallocate($im, 255, 255, 255);
            $tc = imagecolorallocate($im, 0, 0, 0);
            imagefilledrectangle($im, 0, 0, 300, 40, $bgc);
            /* Output an errmsg */
            imagestring($im, 1, 5, 5, "No Image for loading ", $tc);
        }
        if (!$im) { /* See if it failed */
            $im = imagecreate(150, 30); /* Create a blank image */
            $bgc = imagecolorallocate($im, 255, 255, 255);
            $tc = imagecolorallocate($im, 0, 0, 0);
            imagefilledrectangle($im, 0, 0, 300, 40, $bgc);
            /* Output an errmsg */
            imagestring($im, 1, 5, 5, "Error loading $name", $tc);
        }
        $this->file_name = $name;
        $this->image = $im;
        return null;
    }

    function show_resize($percent = 0.5) {
        header("Content-type: image/jpeg");
        list($width, $height) = getimagesize($this->file_name);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresized($thumb, $this->image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($thumb);
        //imagepng($thumb) ;
    }

    function show($type = 'jpeg') {
        /* แสดงภาพตามประเภทที่กำหนด */
        header("Content-type: image/jpeg");
        $im = $this->image;
        switch ($type) {
            case 'jpeg' :
                imagejpeg($im); //สร้างรูป Jpeg
            case 'gif' :
                imagegif($im); //สร้างรูป gif
            default :
                die('Error show_picture ' . $type);
        }

        imagedestroy($im); //คืนหน่วยความจำหลังจากสร้างรูป
        return null;
    }

}

?>
