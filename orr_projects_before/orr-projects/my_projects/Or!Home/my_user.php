<?php

/* * ****************************************************************
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * ค่าใน config.inc.php ให้ถูกต้อง
 * กำหนดข้อมูลของโปรแกรม
 * กำหนดเขตข้อมูล
 * ***************************************************************** */

require_once ('my_page.cls.php');

class my extends my_page {

    function __construct() {
        parent:: __construct();
        $val_ = new OrSysvalue();
        $val_controls = $val_->controls;
        $this->set_skin_ccs("my_form.css");
        /*
         * กำหนดคุณสมบัติของหน้าจอดังนี้
         * $table : ชื่อ Table
         * $sql : คำสั่ง SQL
         * $key : ชื่อ Field ที่เป็น PRIMARY
         */
        $table = 'my_user';
        $sql = 'SELECT * FROM `' . $table . '` ';
        $key = 'id';

        $my_form = new OrDbFrmForm('my_form', $this->get_my_db(), $table, $key);
        $my_form->OP_[list_page_url]->set('my_user_list.php');
        //$my_form->OP_[column]->set(2);
        if ($val_controls[pass] != '') {
            $my_cmd = '$this->set_controls(new OrFieldHidden("val_pass") );';
            $my_cmd .= '$this->val_controls[db_field][val_pass] = md5("' . $val_controls[pass] . '");';
            $my_form->OE_[before_add]->set($my_cmd);
            $my_form->OE_[before_save]->set($my_cmd);
        }
        /*
         * สร้าง Control ในฟอร์ม ประกอบด้วย Class ในกลุ่ม GUI
         */
        $my_form->set_controls(new OrLabel('id'));
        $my_form->controls[id]->OP_[check_null]->set(false);
        $my_form->set_controls(new OrDojoTextbox('user'));
        $my_form->controls[user]->set_clip('user');
        $my_form->controls[user]->set_size(10);

        $my_form->set_controls(new OrTextbox('pass'), 'รหัสผ่าน ', false);
        $my_form->controls[pass]->OP_[title]->set('ควรมีความยาวมากกว่า 6 ');
        $my_form->controls[pass]->set_size(10);
        $my_form->controls[pass]->OP_[password]->set(true);

        $my_form->set_controls(new OrSelectbox('status'));
        $my_form->controls[status]->OP_[option]->set(array(
            '0 Ok' => 0,
            '1 Cancel' => 1
        ));

        $my_form->set_controls(new OrTextbox('prefix'));
        $my_form->controls[prefix]->set_size(10);

        $my_form->set_controls(new OrLabel(''), '', false);

        $my_form->set_controls(new OrTextbox('fname'));
        $my_form->controls[fname]->set_size(20, 50);

        $my_form->set_controls(new OrTextbox('lname'));
        $my_form->controls[lname]->set_size(20, 50);

        /*
         * ตัวอย่างการสร้าง controls textbox ความกว้าง 10 ฟิลด์ชื่อ name
         * $my_form->set_controls(new OrTextbox('name'));
         * $my_form->controls[name]->set_size(10);
         * เพิ่ม control ต่อไว้ด้านล่างนี้
         */

        /*
         * กำหนดข้อมูลการคัดกรองข้อมูล ใหม่กรณีเกิดข้อผิดพลาด เช่น ฟิลด์ name เกิดจากคำสั่ง concat ดังดัวอย่าง
         * $my_form->set_filter_name('name',"concat(`prefix`,`fname`, ' ' , `lname`)");
         */

        /*
         * กระบวนการจัดการข้อมูลจากฐานข้อมูล
         */
        $my_form->fetch_record($sql);

        if ($my_form->controls[user]->OP_[value]->get() != '') {
            $this->set_subpage('my_group_list.php?val_filter[user]=' . $my_form->controls[user]->OP_[value]->get() . '&val_compare[user]==&val_msg[btn_filter]=Filter');
            //$this->set_subpage('my_group_list.php');
        }

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