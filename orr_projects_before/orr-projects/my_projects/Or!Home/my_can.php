<?php

/* * ****************************************************************
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * กรุณาตรวจสอบค่าใน config.inc.php ให้ถูกต้อง
 * หน้าสำหรับกำหนดสิทธิ์การใช้งานโปรแกรมในระบบ
 * ***************************************************************** */

require_once ('my_page.cls.php');

class my extends my_page {

    function __construct() {
        parent:: __construct();
        $this->set_skin_ccs("my_form.css");
        /*
         * กำหนดคุณสมบัติของหน้าจอดังนี้
         * $table : ชื่อ Table
         * $sql : คำสั่ง SQL
         * $key : ชื่อ Field ที่เป็น PRIMARY
         */
        $table = 'my_can';
        $sql = 'SELECT * FROM `' . $table . '` ';
        $key = array('sys_id', 'user');

        $my_sec = new OrSec();
        $user_list = $my_sec->get_user_list();
        $sys_list = $my_sec->get_sys_list();

        $clip_sys_id = new OrClip('sys_id');


        $my_form = new OrDbFrmForm('my_form', $this->get_my_db(), $table, $key);
        $my_form->OP_[list_page_url]->set('my_can_list.php');
        //$my_form->OP_[column]->set(2);
        /*
         * สร้าง Control ในฟอร์ม ประกอบด้วย Class ในกลุ่ม GUI
         */

        $my_form->set_controls(new OrDojoTextSearch('sys_id'));
        $my_form->controls[sys_id]->OP_[default_value]->set($clip_sys_id->OP_[value]->get());
        //$my_form->controls[sys_id]->OP_[option]->set($sys_list);
        $my_form->controls[sys_id]->OP_[popup_url]->set('my_sys_popup_list.php');

        $my_form->set_controls(new OrDojoTextSearch('user'));
        //$my_form->controls['user']->OP_[option]->set($user_list);
        $my_form->controls[user]->OP_[popup_url]->set('my_user_popup_list.php');

        $my_form->set_controls(new OrSelectbox('aut_to_group'));
        $my_form->controls['aut_to_group']->OP_[option]->set(array('ไม่มีสิทธิ์' => '0', 'ให้สิทธ์' => '1'));

        $my_form->set_controls(new OrTextarea('str_sql'));
        $my_form->controls['str_sql']->set_rowcol('5', '50'); //กำหนดจำนวนแถวที่ Row จำนวน
        $my_form->controls['str_sql']->OP_[check_null]->set(false);
        /*
         * ตัวอย่างการสร้าง controls textbox ความกว้าง 10 ฟิลด์ชื่อ name
         * $my_form->set_controls(new OrTextbox('name'));
         * $my_form->controls[name]->set_size(10);
         * เพิ่ม control ต่อไว้ด้านล่างนี้
         */

        /*
         * กระบวนการจัดการข้อมูลจากฐานข้อมูล
         */
        $my_form->fetch_record($sql);
        /*
         * กำหนดส่วนหัวของฟอร์ม ปกติจะแสดงช่อง Filter สำหรับกรองข้อมูล
         */
        $my_form->set_header('ค้นหา ' . $my_form->get_control_filter() . ' เรียง ' . $my_form->get_control_order() . ' ' . $my_form->get_button_filter());
        /*
         * กำหนดฟอร์มลงในหน้า และแสดงหน้าจอ
         */
        $this->set_form($my_form->get_tag());
        $this->set_filter_msg($my_form->OP_[cmd_msg]->get());
        $this->show();
    }

}

$my = new my();
?>
