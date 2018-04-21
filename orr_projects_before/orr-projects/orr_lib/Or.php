<?php
/*Class ส่วนการจัดการพื้นฐาน ของระบบ*/
/*ผู้สร้าง : สุชาติ บุญหชัยรัตน์ : 2/12/2545*/
/*Version 1.1.1(13 พฤษภาคม 2547) ดูรายละเอียดที่ Change. log.txt*/
if($_SERVER['REMOTE_ADDR']=="10.1.16.4")
{
  ini_set('display_errors','On');
}
else
{
  ini_set('display_errors','Off');
}
ini_set('display_errors','On'); //ต้องการแสดง Error
error_reporting(E_ALL ^ E_NOTICE); //แก้ไข การแสดง Error เม่ื่อใช้ร่วมกับ phplot เพื่อทำกราฟ
session_start();
/*เริ่มการกำหนดตัวแปร สภาวะของระบบ*/
require_once('config_lib.inc.php');

/*เริ่ม เตรียมตัวแปรกลาง สำหรับใช้ร่วมในโปรแกรม*/
if($my_cfg_opt[global_register]=='on'){
	session_register('my_sec_user');
	session_register('my_sec_key');
	session_register('my_clip');
}else if($my_cfg_opt[global_register]=='off'){
	$my_sec_user = &$_SESSION['my_sec_user'];
	$my_sec_key = &$_SESSION['my_sec_key'];
	$my_clip = &$_SESSION['my_clip'];
}else{
	die('การกำหนด my_cfg_sec = '.$my_cfg_sec[global_register].'ไม่ถูกต้อง');
}


if (!defined('PMA_GRAB_GLOBALS_INCLUDED')) {
    define('PMA_GRAB_GLOBALS_INCLUDED', 1);

    function PMA_gpc_extract($array, &$target) {
        if (!is_array($array)) {
            return FALSE;
        }
        $is_magic_quotes = get_magic_quotes_gpc();
        reset($array);
        while (list($key, $value) = each($array)) {
            if (is_array($value)) {
                PMA_gpc_extract($value, $target[$key]);
            } else if ($is_magic_quotes) {
                $target[$key] = stripslashes($value);
            } else {
                $target[$key] = $value;
            }
        }
        reset($array);
        return TRUE;
    }

    if (!empty($_GET)) {
        PMA_gpc_extract($_GET, $GLOBALS);
    } else if (!empty($HTTP_GET_VARS)) {
        PMA_gpc_extract($HTTP_GET_VARS, $GLOBALS);
    } // end if

    if (!empty($_POST)) {
        PMA_gpc_extract($_POST, $GLOBALS);
    } else if (!empty($HTTP_POST_VARS)) {
        PMA_gpc_extract($HTTP_POST_VARS, $GLOBALS);
    } // end if

    if (!empty($_FILES)) {
        while (list($name, $value) = each($_FILES)) {
            $$name = $value['tmp_name'];
            ${$name . '_name'} = $value['name'];
        }
    } else if (!empty($HTTP_POST_FILES)) {
        while (list($name, $value) = each($HTTP_POST_FILES)) {
            $$name = $value['tmp_name'];
            ${$name . '_name'} = $value['name'];
        }
    } // end if
    if (!empty($_SERVER)) {
	/*foreach($_SERVER as $id=>$val){
		echo "<b>debug</b> ".__FILE__." | ".__LINE__." | ".$id." = ".$val."<br>";
	}*/
        if (isset($_SERVER['PHP_SELF'])) {
            $PHP_SELF = $_SERVER['PHP_SELF'];
        }
		if (isset($_SERVER['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        }
		if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'];
	    }else if (isset($_SERVER['SCRIPT_NAME'])){
		$SCRIPT_FILENAME = $_SERVER['SCRIPT_NAME'];// for windows 2k
	}
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
    } else if (!empty($HTTP_SERVER_VARS)) {
        if (isset($HTTP_SERVER_VARS['PHP_SELF'])) {
            $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];
        }
		if (isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $HTTP_SERVER_VARS['REMOTE_ADDR'];
        }
		if (isset($HTTP_SERVER_VARS['SCRIPT_FILENAME'])) {
            $SCRIPT_FILENAME = $HTTP_SERVER_VARS['SCRIPT_FILENAME'];
        }
        if (isset($HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'])) {
            $HTTP_ACCEPT_LANGUAGE = $HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'];
        }
    } // end if
}
/*จบ เตรียมตัวแปรกลาง สำหรับใช้ร่วมในโปรแกรม*/
function debug_mode($file , $line , $value , $description ){
	global $my_cfg_opt;
	if($my_cfg_opt[debug] == 'on')
	{
		if(is_array($value))
		{
			foreach($value as $id => $val)
			{
				$str_value .= '[' . $id . '] = ' . $val . ' , ';
			}
			$value = $str_value;
		}
		echo "<b>Debug_mode</b> " . $file . " | " . $line . " | " . $description . " | value [" . $value . " ] <br> " ;
	}
	return null;
}
//sys
require_once('sys/OrSysvalue.php');
require_once('sys/OrProperty.php');
require_once('sys/OrObj.php');
require_once('sys/OrSec.php');
require_once('sys/OrClip.php');
require_once('sys/OrFormat.php');
require_once('sys/OrThdate.php');

//gui
require_once('gui/OrGui.php');
require_once('gui/OrPicture.php');
require_once('gui/OrAjax.php');
require_once('gui/OrAccordionAjax.php');
require_once('gui/OrGraph.php');
require_once('gui/OrHtml.php');
require_once('gui/OrSkin.php');
require_once('gui/OrForm.php');
require_once('gui/OrControls.php');
require_once('gui/OrTextbox.php');
require_once('gui/OrTextCalendar.php');
require_once('gui/OrTextCalendar2.php');
require_once('gui/OrTextarea.php');
require_once('gui/OrLabel.php');
require_once('gui/OrImage.php');
require_once('gui/OrButton.php');
require_once('gui/OrSelectbox.php');
require_once('gui/OrCheckbox.php');
require_once('gui/OrFieldHidden.php');
require_once('gui/OrFieldProperty.php');
require_once('gui/OrJs.php');
require_once('gui/OrJsMenu.php');
require_once('gui/OrJsCalendar.php');
require_once('gui/OrTable.php');
require_once('gui/OrLabelAjax.php');
require_once('gui/OrSelectboxAjax.php');
require_once('gui/OrContent.php');

//ajax
require_once('ajax/OrPage.php');
require_once('ajax/OrMenu.php');

//Dojo
require_once('dojo/OrDojoForm.php');
require_once('dojo/OrDojoTextbox.php');
require_once('dojo/OrDojoTextarea.php');
require_once('dojo/OrDojoSelectbox.php');
require_once('dojo/OrDojoButton.php');
require_once('dojo/OrDojoTextSearch.php');
require_once('dojo/OrDojoChart.php');

//db
require_once('db/OrDb.php');
require_once('db/OrDbForm.php');
require_once('db/OrDbFrmForm.php');
require_once('db/OrDbFormList.php');
require_once('db/OrDbFrmList.php');
require_once('db/OrDbPopupList.php');
require_once('db/OrDbFrmCrossTab.php');
require_once('db/OrMysql.php');
require_once('db/OrSql.php');
require_once('db/OrSqlCrossTab.php');
require_once('db/OrMySqlThDate.php');
require_once('db/OrMySqlThDatetime.php');

?>
