<?php


/******************************************************************
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * กรุณาตรวจสอบค่าใน config.inc.php ให้ถูกต้อง
 *******************************************************************/

require_once('my_page.cls.php');

class my extends my_page {
	function __construct() {
		parent :: __construct();
		$this->set_skin_ccs("my_form.css");
		$my_sec = new OrSec();
		$user_list = $my_sec->get_user_list();
		/*
		 * กำหนดคุณสมบัติของหน้าจอดังนี้
		 * $table : ชื่อ Table
		 * $sql : คำสั่ง SQL
		 * $key : ชื่อ Field ที่เป็น PRIMARY
		 */
		$table = 'my_group';
		$sql = 'SELECT * FROM `' . $table . '` ';
		$key = array('group','user');
                
        $clip_user = new  OrClip('user');
                
		$my_form = new OrDbFrmForm('my_form', $this->get_my_db(), $table, $key);
		$my_form->OP_[list_page_url]->set('my_group_list.php');
		$my_form->OP_[column]->set(3);
		
		/*$my_form->OE_[after_add]->set($my_cmd);
		$my_form->OE_[after_save]->set($my_cmd);
		$my_form->OE_[after_delete]->set($my_cmd);*/
		
		/*
		 * สร้าง Control ในฟอร์ม ประกอบด้วย Class ในกลุ่ม GUI
		 */
		//$my_form->set_controls(new OrLabel('id'));
		//$my_form->controls[id]->OP_[check_null]->set(false);
		/*
		 * ตัวอย่างการสร้าง controls textbox ความกว้าง 10 ฟิลด์ชื่อ name
		 * $my_form->set_controls(new OrTextbox('name'));
		 * $my_form->controls[name]->set_size(10);
		 * เพิ่ม control ต่อไว้ด้านล่างนี้
		 */
		$my_form->set_controls(new OrSelectbox('group'));
		$my_form->controls[group]->OP_[option]->set($user_list);

		$my_form->set_controls(new OrSelectbox('user'));
                $my_form->controls['user']->OP_[default_value]->set($clip_user->OP_[value]->get());
		$my_form->controls['user']->OP_[option]->set($user_list);

		$my_form->set_controls(new OrTextbox('description'));
		$my_form->controls[description]->set_size(20, 50);
		$my_form->controls[description]->OP_[check_null]->set(false);
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
		$this->set_form($my_form->get_tag($this->get_skins_path('form_button.html')));
		$this->set_filter_msg($my_form->OP_[cmd_msg]->get());
		$this->show();
	}

}
$my = new my();
?>
