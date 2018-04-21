<?php
 /**
 * my_page.cls.php
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @package    Or!Lib
 * @author     Suchart Bunhachirat <suchart_bu@yahoo.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    2550
 */
 
require_once('../../orr_lib/Or.php');
require_once('config.inc.php');

 /**
 * Class สำหรับสร้างหน้าจอมาตรฐาน
 * @package    Or!Lib
 * @author     Suchart Bunhachirat <suchart.orr@gmail.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    2554
 */
class my_page extends OrHtml
{
	public $caption = null;
        //public $skin = null;
	/**
	 * การใช้ Class my_page
	 * 
	 * 
	 */
	function __construct($title = ''){
		global $my_cfg;
		$my_sec = new OrSec(false);
		//$caption = 'ทดสอบ Caption';
		$caption = $my_sec->OP_[title]->get();
		debug_mode(__FILE__ , __LINE__ , $my_sec->OP_[title]->get() , 'Title');
		if($title == '')
		{
			$title .= $my_cfg[title] . ' : ' . $caption;
		}
		parent :: __construct($title);
		//$this->set_ccs_src($my_cfg[skins_path] . 'my_page.css');
		$this->set_skin($my_cfg[skins_path] .'default.html');//รูปแบบหน้าจอปกติ
		//$my_menu = new OrJsMenu('my_menu_utf8.inc.js' , $my_cfg[skins_path] . "xp.css");
		//$this->set_ccs_src($my_menu->OP_[js_ccs]->get());
		//$this->set_script_src($my_menu->OP_[js_src]->get());
		//$my_calendar = new OrJsCalendar();
		//$this->set_ccs_src($my_calendar->OP_[js_ccs]->get());
		//$this->set_script_src($my_calendar->OP_[js_src]->get());
		//$this->OP_[script_event_body]->set($my_menu->get_script_event('OnLoad'));
		/**
		 * คำสั่งใช้ Ajax Copy ที่ต้องการใช้งาน
		 * $ajax = new OrAjax();
		 * $this->set_script_src($ajax->OP_[ajax_src]->get());
		 * $this->set_script($ajax->require_tooltip());
		 * $this->set_ccs_src($ajax->require_tooltip_css());
		 */
		$this->set_title( $my_cfg[title]);
                $this->set_caption($caption);
                $link_logout = '<a href="welcome.php?val_controls[logout]=logout" >ออกจากระบบ</a>';
                $this->set_login( $my_sec->get_user_text() . '</b> [ <u>' . $my_sec->OP_[user]->get() . '</u> ]' . $link_logout);
	}
        
        
	/**
	 * set_skin_ccs : กำหนดไฟล์ Styles Sheet
	 * @param string $file_name ชื่อไฟล์ที่อยู่ใน skin path
	 * @return null
	 */
	function set_skin_ccs($file_name)
	{
		global $my_cfg;
		$this->set_ccs_src($my_cfg[skins_path].$file_name);
		return null;
	}
	/**
	 * set_caption : กำหนดหัวเรื่องโปรแกรม
	 * @param string $tag ชื่อโปรแกรม
	 * @return null
	 */
	function set_caption($tag){
		$this->skin->set_skin_tag('my_caption' , $tag);
		$this->caption = $tag;
		return null;
	}
        /**
	 * set_caption : กำหนดชื่อระบบ
	 * @param string $tag ชื่อระบบ
	 * @return null
	 */
	function set_title($tag){
		$this->skin->set_skin_tag('my_title' , $tag);
		$this->caption = $tag;
		return null;
	}
	/**
	 * set_form : กำหนด Form
	 * @param string $tag คำสั่ง HTML ของ Form
	 * @return null
	 */
	function set_form($tag){
		$this->skin->set_skin_tag('my_form' , $tag);
		return null;
	}

        /**
	 * set_form : กำหนด Form เพื่อ Login
	 * @param string $tag คำสั่ง HTML ของ Form
	 * @return null
	 */
	function set_login($tag){
		$this->skin->set_skin_tag('my_login' , $tag);
		return null;
	}
        /**
	 * set_form : กำหนด Form เพื่อ Login
	 * @param string $tag คำสั่ง HTML ของ Form
	 * @return null
	 */
	function set_leading($tag){
		$this->skin->set_skin_tag('my_leading' , $tag);
		return null;
	}
	/**
	 * set_subpage : กำหนด Sub Page
	 * @param string $url Address ของ Page
	 * @return null
	 */
	function set_subpage($url , $width = '100%'  , $height = '99%')
	{
		$tag = '<iframe id="frm_sub" name="frm_sub" style="margin:0px;padding:0px" frameborder="0" height="' . $height . '" width="' . $width . '" scrolling="auto" src="' . $url . '" > Or!Frame</iframe>';
		$this->skin->set_skin_tag('my_subform' , $tag);
		return null;
	}
	/**
	 * set_status : กำหนด สถานะการทำงาน
	 * @param string $tag ข้อความแจ้งสถานะ
	 * @return null
	 */
	function set_status($tag){
		global $my_cfg;
		if($tag != '')
		{
			$icon = '<img src="skins/default/image/button/info.png" title=" สถานะ : ' . $tag . '">';
			$this->skin->set_skin_tag('my_status' , $icon );
		}
		return null;
	}
	/**
	 * set_filter : กำหนด สถานะการ Filter
	 * @param string $tag ข้อความแจ้ง เงื่อนไขการคัดกรองข้อมูล
	 * @return null
	 */
	function set_filter_msg($tag){
		global $my_cfg;
		if($tag != '')
		{
			/*$icon = '<img src="skins/default/image/button/filter_msg.png" title=" เงื่อนไข : ' . $tag . '">';
			$this->skin->set_skin_tag('my_filter_msg' , $icon );*/
			$this->set_caption($this->caption .  ' [ ' . $tag . ' ]');
		}
		return null;
	}
	/**
	 * set_my_message : กำหนด ข้อความแจ้งผู้ใช้ระบบ
	 * @param string $tag ข้อความแจ้ง ข้อมูล
	 * @return null
	 */
	function set_my_message($tag){
		global $my_cfg;
		if($tag != '')
		{
			$icon = '<img src="skins/default/image/button/message.png" title=" ข้อมูล : ' . $tag . '">';
			$this->skin->set_skin_tag('my_message' , $icon );
		}
		return null;
	}
	/**
	 * set_footer : กำหนด Footer
	 * @param string $tag ข้อความส่วน Footer
	 * @return null
	 */
	function set_footer($tag){
		global $my_cfg;
		$skin_footer = new OrSkin($my_cfg[skins_path]."my_footer.html");
		$skin_footer->set_skin_tag('my_footer' ,$tag);
		$this->skin->set_skin_tag('my_footer' , $skin_footer->get_tag());
		return null;
	}
	
	/**
	 * get_frame : html tag iframe
	 * @param string Url of page in frame
	 * @return string html tag
	 */
	function get_frame($page_url){
		return $my_value = '<iframe name="frm_page" frameborder="0" height="100%" width="100%" scrolling="auto" src="' . $page_url . '" > Or!Frame</iframe>';
	}
	
	/**
	 * get_skins_path : ค่า path และ file_name ตาม $my_cfg[skins_path]
	 * @param string $file_name ชื่อไฟล์
	 * @return string ค่า path + ชื่อไฟล์
	 */
	function get_skins_path($file_name)
	{
		global $my_cfg;
		$my_value = $my_cfg[skins_path] . $file_name;
		return $my_value;
	}
	/**
	 * get_my_db : ค่า DB Object ฐานข้อมูลที่กำหนดจาก $my_cfg[db]
	 * @return object DB Object จาก Class OrMysql
	 */
	function get_my_db()
	{
		global $my_cfg;
		$my_db = new OrMysql($my_cfg[db]);
		return $my_db;
	}
	/**
	 * get_text : ค่า ข้อความจากไฟล์
	 * @param string ชื่อไฟล์
	 * @return string ข้อความในไฟล์	
	 */
	function get_text($file_name){
		return $my_value = file($file_name);
	}
	/**
	 * get_button_filter : html tag ของปุ่ม filtter
	 * @param string $cmd_filter คำสั่ง filter ข้อมูล
	 * @return string html tag ของปุ่ม filter
	 */
	function get_button_filter($cmd_filter=''){
		echo 'ยกเลิกคำสั่ง get_button_filter ใน my_page';
		global $my_cfg;
		$btn_filter=new OrButton('btn_filter','val_msg[btn_filter]');
		$btn_filter->OP_[class_name]->set("toolbar");
		$btn_filter->OP_[title]->set("ค้นหา");
		$btn_filter->OP_[value]->set("Filter");
		$btn_filter->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/filter.png');
		$my_tag = $btn_filter->get_tag();
		
		$btn_query=new OrButton('btn_query','val_msg[btn_query]');
		$btn_query->OP_[class_name]->set("toolbar");
		$btn_query->OP_[title]->set("ตั้งคำถาม");
		$btn_query->OP_[value]->set("Query");
		$btn_query->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/query.png');
		$my_tag .= $btn_query->get_tag();
		
		if($cmd_filter != '')
		{
			$btn_filter->OP_[title]->set("ยกเลิก ค้นหา");
			$btn_filter->OP_[value]->set("No Filter");
			$btn_filter->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/no_filter.png');
			$my_tag .= $btn_filter->get_tag();
		}
		
		return $my_tag;
	}
	 
	 /**
	 * get_fitter : sql filter สร้างคำสั่งค้นหาข้อมูล
	 * @param string $str_filter	ข้อความที่ต้องการ filter
	 * @return string sql filter
	 */
	function get_filter($str_filter){
		/*ชุดคำสั่งตรวจสอบ รูปแบบการคันหาข้อมูล กรณีใส่ ' ' หมายถึงค้นหาอย่างกำหนดตามเงื่อนไขในเครื่องหมาย */
		/*แต่ถ้าไม่กำหนดมาให้ค้นหา บ้างส่วนของข้อมูลอย่างอัตโนมัติ สุชาติ บุญหชัยรัตน์ 23/8/2547 */
		/*ยกเลิกคำสั่งนี้ เพราะได้รวมความสามารถไปไว้ที่ OrSql แทน*/
		echo "<b>ยกเลิกการใช้คำสั่งนี้แล้ว กรุณาแจ้งผู้ดูแลระบบ !</b> ".__FILE__." | ".__LINE__ ."<br>";
		return $str_filter;
	}
        
        /**
	 * show : คำสั่งแสดงหน้าจอ Html
	 * @return null
	 */
	
	 function show()
	{
		//$this->set_body($this->skin->get_tag());
		parent::show();
		return null;
	}
}
