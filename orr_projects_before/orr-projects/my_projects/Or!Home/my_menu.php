<?php

/**
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * ค่าใน config.inc.php ให้ถูกต้อง
 * กำหนดข้อมูลของโปรแกรม
 * กำหนดเขตข้อมูล
 * */
require_once ('my_page.cls.php');

class my extends my_page {

    function __construct() {
        parent:: __construct();
        global $my_cfg;
        $this->set_skin_ccs("my_form.css");
        /*
         * กำหนดคุณสมบัติของหน้าจอดังนี้
         * $table : ชื่อ Table
         * $sql : คำสั่ง SQL
         * $key : ชื่อ Field ที่เป็น PRIMARY
         */
        $table = 'my_menu';
        $sql = 'SELECT * FROM `' . $table . '` ';
        $key = 'id';

        $my_form = new OrDbFrmForm('my_form', $this->get_my_db(), $table, $key);
        //$my_form->OP_[list_page_url]->set('_list.php');
        $my_form->OP_[column]->set(1);

        /*
         * สร้าง Control ในฟอร์ม ประกอบด้วย Class ในกลุ่ม GUI
         */
        $my_form->set_controls(new OrLabel('id'));
        $my_form->controls[id]->OP_[check_null]->set(false);
        /*
         * ตัวอย่างการสร้าง controls textbox ความกว้าง 10 ฟิลด์ชื่อ name
         * $my_form->set_controls(new OrTextbox('name'));
         * $my_form->controls[name]->set_size(10);
         * เพิ่ม control ต่อไว้ด้านล่างนี้
         */
        //$my_form->set_controls(new OrLabel('blank'),'สำหรับเพิ่มช่องว่าง'); //TODO : ทดสอบ controls สำหรับจัดแต่งหน้าจอ
        
        $my_form->set_controls(new OrSelectbox('category_id'));
        $my_form->controls[category_id]->OP_[option]->set($my_cfg[menu_category]);    
                
        $my_form->set_controls(new OrTextbox('name'));
        $my_form->controls[name]->set_size(50);
        
        $my_form->set_controls(new OrTextbox('sort_id'));
        $my_form->controls[sort_id]->set_size(3);

        $my_form->set_controls(new OrTextbox('href'));
        $my_form->controls[href]->set_size(80);

        $my_form->set_controls(new OrSelectbox('href_type'));
        $my_form->controls[href_type]->OP_[option]->set(array (
			'ปกติ' => 0,
			'หน้าใหม่' => 1,
                        'หน้าเดิม' => 2
		));
        $my_form->set_controls(new OrSelectbox('status'));        
        $my_form->controls[status]->OP_[option]->set(array (
			'Ok' => 0,
			'Cancel' => 1
		));
        //$my_form->set_controls(new OrDojoTextarea('href'));
        /*
         * กำหนดข้อมูลการคัดกรองข้อมูล ใหม่กรณีเกิดข้อผิดพลาด เช่น ฟิลด์ name เกิดจากคำสั่ง concat ดังดัวอย่าง
         * $my_form->set_filter_name('name',"concat(`prefix`,`fname`, ' ' , `lname`)");
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
        //$this->set_my_message($my_form->OP[message]->get());
        $this->show();
    }

}

$my = new my();
?>
