<?php
/******************************************************************
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * กรุณาตรวจสอบ
 * ค่าใน config.inc.php ให้ถูกต้อง
 * กำหนดข้อมูลของโปรแกรม
 * กำหนดเขตข้อมูล
 *******************************************************************/
 
require_once('my_page.cls.php');
require_once('or!config.cls.php');

class my extends my_page {
	function __construct()
	{
		parent:: __construct();
		$ajax = new OrAjax();
		$this->set_script_src($ajax->OP_[ajax_src]->get());
		$this->set_script($ajax->require_tooltip());
		$this->set_ccs_src($ajax->require_tooltip_css());
		
		$this->set_skin_ccs("my_list.css");
		$this->set_skin_ccs("my_user_list.css");
		/*
		 * กำหนดคุณสมบัติของหน้าจอดังนี้
		 * $sql : คำสั่ง SQL
		 */
		$sql = "SELECT * ,concat(`prefix`,`fname`, ' ' , `lname`) AS `name` FROM `my_user`";
		
		$my_form = new OrDbFrmList('my_form' , $this->get_my_db() );
                $my_form->OP_[edit_page_url]->set('my_user.php');
                $my_form->OP_[edit_field_link]->set('user');
                $my_form->OP_[edit_key_field]->set('id');
		$my_form->OE_[current_record]->set("include('my_user_list.OE_current_record.php');");
		/*
		 * สร้าง Control ในฟอร์ม ประกอบด้วย Class ในกลุ่ม GUI
		 */
		
		$my_form->set_controls(new OrLabel('id'));
		$my_form->set_controls(new OrLabelAjax('user'));
		$my_form->set_controls(new OrLabel('name'));
		$my_form->set_controls(new OrLabel('status'));
		
		$my_form->set_filter_controls(new select_ok_cancel('status'));
		
		/*
		 * กำหนด Function คำนวณการคำสั่ง SQL
		 */
		 
		
		/*
		 * กำหนดข้อมูลการคัดกรองข้อมูล ใหม่กรณีเกิดข้อผิดพลาด เช่น ฟิลด์ name เกิดจากคำสั่ง concat ดังดัวอย่าง
		 * $my_form->set_filter_name('name',"concat(`prefix`,`fname`, ' ' , `lname`)");
		 */
		 $my_form->set_filter_name('name',"concat(`prefix`,`fname`, ' ' , `lname`)");
		/*
		 * กระบวนการจัดการข้อมูลจากฐานข้อมูล
		 */
		$my_form->fetch_record($sql);
		/*
		 * กำหนดส่วนหัวของฟอร์ม ปกติจะแสดงช่อง Filter สำหรับกรองข้อมูล
		 */
		 
		 /*
		  * กำหนดส่วนล่างของฟอร์ม กรณีที่ต้องการ เช่นแสดง ยอดรวม
		  */
		 // $my_form->set_footer('จำนวน ' . $my_form->total_controls[count_name]->get_tag() . ' คน<br>รหัสล่าสุด ' . $my_form->total_controls[max_id]->get_tag() . ' --- ');
		 /*
		 /*
		  * กำหนดฟอร์มลงในหน้า และแสดงหน้าจอ
		  */
		 $this->set_form( $my_form->get_tag());
		 $this->set_filter_msg( $my_form->OP_[cmd_msg]->get());
		 $this->show();
	}

}
$my = new my();
?>
