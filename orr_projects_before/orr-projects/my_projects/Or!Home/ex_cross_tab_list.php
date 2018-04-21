<?php
/******************************************************************
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * กรุณาตรวจสอบ
 * ค่าใน config.inc.php ให้ถูกต้อง
 * กำหนดข้อมูลของโปรแกรม
 * กำหนดเขตข้อมูล
 * ตัวอย่างการเขียนเพื่อแสดงข้อมูลแบบรายงาน
 *******************************************************************/
require_once('my_page.cls.php');

class my extends my_page {
	function __construct()
	{
		parent:: __construct();
		$this->set_skin_ccs("my_list.css");
		/*
		 * กำหนดไฟล์ css ที่ใช้กำหนดความกว้างในแต่ละช่องข้อมูล
		 * โดยปกติจะตั้งชื่อเดียวกับ ชื่อไฟล์โปรแกรมแต่มีนามสกุลเป็น .css
		 * อ่านรายละเอียด การกำหนดค่าได้ในไฟล์ new_page_list.css
		 */
		$this->set_skin_ccs("new_page_list.css");//<-กำหนดชื่อไฟล์ css
		/*
		 * กำหนดคำสั่ง SQL ที่ใช้ในการแสดงข้อมูลในฐานข้อมูล ในดัวแปร $sql
		 * ตัวอย่างเป็นแสดงข้อมูลจากตาราง my_user
		 */
		
		$sql = "SELECT  `sec_user` FROM `my_activity` GROUP BY `sec_user`";//<-กำหนดคำสั่ง SQL
		//$graph= new OrGraph('การใช้งาน Script','ครั้ง','',600 ,400);
 		/*$data_array[1]  = array(11,3, 8,10,5 ,1,9, 13,5,7 ,20,12); 
 		$data_array[2]  = array(8,2, 18,20,9 ,3,5, 21,18,15 ,18,15);
 		$data_array[3]  = array(2,12, 8,12,15 ,23,15, 13,28,25 ,30,25);
 		$data_array[4]  = array(26,20,12,21,13 ,32,25, 3,20,23 ,10,5);
 		$graph->set_data_array($data_array);*/
 		
 		
		/*$cross_tab = new OrSqlCrossTab($sql);
 		$cross_tab->OP_[col_field_name]->set('status');
 		$cross_tab->OP_[data_field_name]->set('status');
 		$sql = $cross_tab->get_sql($this->get_my_db() , $sql);*/
		
		$my_form = new OrDbFrmCrossTab('my_form' , $this->get_my_db() );
		$my_form->set_cross_tab('sec_script' , 'sec_user' , 'count');
		/*
		 * กำหนดคำสั่งที่ต้องในเหตุการณ์ของ Form เช่น on current record โดยปกติจากสร้างไฟล์เก็บคำสั่งไว้
		 * โดยใช้ [ชื่อไฟล์โปรแกรม] .[ชื่อเหตุการณ์] เช่น new_page_list.OE_current_record.php เป็นต้น
		 * สามารถดูรายละเอียดได้ในไฟล์ดังกล่าว
		 */
		//$my_form->OE_[current_record]->set("include('new_page_list.OE_current_record.php');");//<-แก้ไขถ้าต้องการใช้คำสั่งตามเหตุการณ์
		
		/*
		 * สร้าง Control ในฟอร์ม โดยปกติจะใช้ class OrLabel
		 * ตามตัวอย่างประกอบด้วยฟิลด์ตามคำสั่ง SQL ในตาราง my_user
		 */
		
		$my_form->set_controls(new OrLabel('sec_user') );
		//$my_form->OP_[default_mode]->set('list');
		
		/*
		 * กำหนด Function คำนวณการคำสั่ง SQL
		 */
		 //$my_form->set_total_function('col_1' , '');
		/*
		 * กำหนดข้อมูลการคัดกรองข้อมูล ใหม่กรณีเกิดข้อผิดพลาด เช่น ฟิลด์ name เกิดจากคำสั่ง concat ดังดัวอย่าง
		 * $my_form->set_filter_name('name',"concat(`prefix`,`fname`, ' ' , `lname`)");
		 */
		 //$my_form->set_filter_name('name',"concat(`prefix`,`fname`, ' ' , `lname`)");//<-แก้ไขคำสั่งที่ใช้แทนเวลาที่กรองข้อมูล
		
		/*
		 * กระบวนการจัดการข้อมูลจากฐานข้อมูล
		 */
		$my_form->fetch_record($sql);
		/*if($my_form->OP_[on_load]->get() ==0 ){
			$my_form->form_mode = 'query';
		}
		/*
		 * กำหนดส่วนหัวของฟอร์ม ปกติจะแสดงช่อง Filter สำหรับกรองข้อมูล
		 */
		 //$my_form->set_header('ค้นหา ' . $my_form->get_control_filter() .' เรียง ' . $my_form->get_control_order() . ' ' . $my_form->get_button_filter() . '<br>' );
		 /*
		  * กำหนดส่วนล่างของฟอร์ม กรณีที่ต้องการ เช่นแสดง ยอดรวม
		  */
		 //$my_form->set_footer('รวมทั้งหมด ' . $my_form->total_controls[_col_1]->get_tag() . ' รายการ');
		 /*
		  * กำหนดฟอร์มลงในหน้า และแสดงหน้าจอ
		  */
		 $this->set_form($my_form->get_tag());
		/* if($my_form->form_mode == 'list'){
			 $graph->set_data_array($my_form->page_data);
			 $graph->create_image();
		 }*/
		 $this->set_filter_msg( $my_form->OP_[cmd_msg]->get());
		 $this->show();
	}

}
$my = new my();
?>
