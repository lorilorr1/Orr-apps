<?php
require_once('my_page.cls.php');

/**
 * Class ตัวอย่างการใช้ Class my_page สร้าง form เพื่อบันทึกแก้ไข ข้อมูลใน ฐานข้อมูล
 * Save as เพื่อแก้ไขใช้งาน ตามคำอธิบาย
 * @package    Or!Lib
 * @author     Suchart Bunhachirat <suchart_bu@yahoo.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    2550
 */
 
class my extends my_page
{
	/**
	 * กำหนดคุณสมบัติของหน้าจอ มีตัวแปรที่ต้องกำหนดค่าคือ
	 * $sql : คำสั่งเพื่อคัดข้อมูลจาก Table
	 * $table : ชื่อ Table
	 * $key_field : ชื่อ Field ที่เป็น PRIMARY
	 * $my_form : Object เพื่อจัดการเกี่ยวกับ Form และข้อมูล
	 * ตรวจสอบค่าใน config.inc.php มีค่าถูกต้อง
	 */
	 
	function __construct()
	{
		parent::__construct();
		/*$ajax = new OrAjax();
		$this->set_script_src($ajax->OP_[ajax_src]->get());
		$this->set_script($ajax->require_selectbox());*/
		$this->set_skin_ccs("my_form.css");
		
		/**กำหนดค่าตัวแปร ของหน้า**/
		$sql = 'SELECT * FROM `my_sys` ';
		$my_sec = new OrSec();
		/****************/
		/**สร้าง Form กำหนด Control***/		
		$my_form = new OrDbFrmForm('my_form' , $this->get_my_db() , 'my_sys' , 'sys_id' );
                $my_form->OP_[list_page_url]->set('my_sys_list.php');
		$my_form->OP_[column]->set(2);
		
                $my_form->set_controls(new OrDoJoTextbox('sys_id'));
		$my_form->controls[sys_id]->set_size(20,50);
                $my_form->controls[sys_id]->set_clip('sys_id');

		$my_form->set_controls(new OrSelectbox('any_use'));
		$my_form->controls[any_use]->OP_[option]->set(array('0 ระบุ'=>0,'1 ไม่ระบุ'=>1));
		
		$opt=array();
		$opt["0 ไม่มีสิทธิ์ใช้งาน"]=0;
		$opt["1 สิทธิ์อ่านข้อมูล"]=1;
		$opt["2 สิทธิ์อ่านเขียน"]=2;
		$opt["3 สิทธิ์อ่านเขียนลบ"]=3;
		
		$my_form->set_controls(new OrSelectbox('aut_user'));
		$my_form->controls[aut_user]->OP_[option]->set($opt);
		
		$my_form->set_controls(new OrSelectbox('aut_group'));
		$my_form->controls[aut_group]->OP_[option]->set($opt);
		
		$my_form->set_controls(new OrSelectbox('aut_any'));
		$my_form->controls[aut_any]->OP_[option]->set($opt);
		
		$my_form->set_controls(new OrSelectbox('aut_god'));
		$my_form->controls[aut_god]->OP_[option]->set(array('0 ไม่'=>0,'1 ใช่'=>1));
		
		$my_form->set_controls(new OrDojoSelectbox('aut_can_from'));
		$my_form->controls[aut_can_from]->OP_[option]->set(array_merge(array('ไม่กำหนด'=>''),$my_sec->get_sys_list()));
		$my_form->controls[aut_can_from]->OP_[check_null]->set(false);
		
		$my_form->set_controls(new OrTextbox('title'));
		$my_form->controls[sys_id]->set_size(20,50);
		
		$my_form->set_controls(new OrDojoTextarea('description'));
		$my_form->controls['description']->set_rowcol('3','50'); //กำหนดจำนวนแถวที่ Row จำนวน
		$my_form->controls['description']->OP_[check_null]->set(false);
		
		$my_form->fetch_record($sql);
		
		if($my_form->controls[any_use]->OP_[value]->get() == '0')
		{
			//$link = 'my_can.php?val_filter[sys_id]=' . $my_form->controls[sys_id]->OP_[value]->get() .'&val_msg[btn_filter]=Filter';
			//$link = 'javascript:open("my_sys_list.php",null,"height=800,width=1000,status=yes,toolbar=no,menubar=yes,location=no");';
                        //$my_form->controls[any_use]->OP_[description]->set('<a href=\'' . $link . '\' ><img src="' . $this->get_skins_path("/image/button/link_about.png") . '" title="คลิกกำหนดผู้ใช้ระบบ"></a>');
                        $this->set_subpage('my_can_list.php?val_filter[sys_id]=' . $my_form->controls[sys_id]->OP_[value]->get() . '&val_compare[sys_id]==&val_msg[btn_filter]=Filter');
		}
		
		$my_form->set_header( $my_form->get_control_filter() . ' ' . $my_form->get_button_filter());
		//$my_message = 'ข้อมูลต่างๆ ที่ต้องการแจ้ง ผู้ใช้ระบบ';
		$this->set_form( $my_form->get_tag($this->get_skins_path('form_button.html')));
		$this->set_status( $my_form->OP_[message]->get() ); //TODO :  version 2554 แจ้งใน my_form แทน
		$this->set_filter_msg( $my_form->OP_[cmd_msg]->get()); //TODO : version 2554 แจ้งใน my_form แทน
		//$this->set_my_message( $my_message);
		//$this->set_footer($this->get_text('footer.inc'));
		
		$this->show();
	}
}
/*เรียกใช้ Class เพื่อทำงาน*/
$my = new my();
unset($my);
?>
