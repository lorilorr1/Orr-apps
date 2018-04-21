<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../../orr_lib/Or.php');
require_once('config.inc.php');

/**
 * Class สำหรับรับส่งข้อมูลแบบ Ajax
 * @package    Or!Lib
 * @author     Suchart Bunhachirat <suchart.orr@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    2554
 */
class my extends OrContent {

    /**
     * ตรวจสอบค่าเริ่มต้นของคลาส
     * @param null
     * @return null
     */
    function __construct() {
        //global $my_cfg;
        parent::__construct();
        /*
         * 1. $val_ รับค่าที่ส่งมาจากการ $_GET หรือ $_POST จาก OrSysvalue
         * 2. $my_sec ตรวจสอบสิทธิ์การใช้งาน
         * 3. $my_db เข้าใช้งานฐานข้อมูล
         */
        $val_ = new OrSysvalue();
        if ($val_->message[content_key_value] == 'accept') {
            if($val_->message[my_note_id] > 0){
                $this->accept_note($val_->message[my_note_id]);
            }else{
                $this->set_body('คุณได้เคยอ่านประกาศล่าสุดไปแล้ว');
            }
        } else {
            $this->last_note($val_->message[content_key_value]);
        }
        //$my_sec = new OrSec();

        $this->show();
    }

    /**
     * accept_note : บันทึกการอ่านประกาศ เพื่อไม่ให้แสดงมาซ้ำเดิมอีกหลังจากอ่านไปแล้ว
     * @param string $id รัหสประกาศที่อ่าน
     * @return null
     */
    function accept_note($id) {
        global $SCRIPT_FILENAME, $REMOTE_ADDR, $my_cfg_sec;
        $my_db = new OrMysql($my_cfg_sec[db]);
        $my_script = basename($SCRIPT_FILENAME);
        $sql = "UPDATE `my_registration` SET `last_note_id` = '" . $id . "' , `accept_note` = NOW() WHERE sec_ip='" . $REMOTE_ADDR . "';";
        $my_db->get_query($sql);
        if ($my_db->is_error()

            )$my_db->show_error();
        unset($my_db);
        $this->set_body('คุณได้อ่านประกาศเลขที่ ' . $id . ' ไปแล้ว');
        return NULL;
    }

    /**
     * last_note : แสดงประกาศล่าสุด
     * @param null
     * @return null
     */
    function last_note() {
        global $SCRIPT_FILENAME, $REMOTE_ADDR, $my_cfg_sec;
        $my_db = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        //$sql = "SELECT `detail` FROM `my_note` WHERE `sec_time` = '" . $val_->message[content_key_value] . "'"; //(กำหนด SQL ตามเงื่อนไขที่ต้องการ)
        $my_skin = new OrSkin('my_note.html');
        //ตรวจสอบเวลาที่ได้อ่านประกาศล่าสุดจาก my_registration
        $sql = "SELECT * FROM `my_registration` WHERE `sec_ip` = '" . $REMOTE_ADDR . "'";
        $my_db->get_query($sql);
        if ($my_db->get_record()) {
            $my_accept_note = $my_db->record[accept_note];
        } else {
            $my_accept_note = 0;
        }
        //อ่านประกาศล่าสุด และที่คาดว่าไม่ได้อ่าน
        $sql = "SELECT * FROM `my_note` WHERE `sec_time` >= '" . $my_accept_note . "' ORDER BY `sec_time` DESC LIMIT 10";
        $my_db->get_query($sql);
        $i = 0;
        $my_skin->set_skin_tag('id', '');
        $my_skin->set_skin_tag('detail', 'ประกาศใหม่ๆ ยังไม่มีนะคะ');
        while ($my_db->get_record()) {
            
            $my_detail = $my_db->record[detail];
            $my_detail .= 'วันเวลาที่บันทึก : ' . $my_db->record[sec_time].'<br>';
            
            if ($i == 0) {
                $my_skin->set_skin_tag('id', $my_db->record[id]);
                $my_skin->set_skin_tag('detail', $my_detail);
            } else {
                $my_remain .= '<hr>' . $my_detail;
            }
            $i++;
        }
        $my_skin->set_skin_tag('remain', $my_remain);
        unset($my_db);

        $this->set_body($my_skin->get_tag());
        return NULL;
    }

}

//เริ่มการทำงานของคลาส
$my_content = new my();
?>
