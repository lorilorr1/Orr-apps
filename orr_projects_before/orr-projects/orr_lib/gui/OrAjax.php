<?php
//
//@version php5
//@author Suchart Bunhachirat
//@copyright Copyright &copy; 2007, orr
//Class เรียกใช้ Ajax
//*****************************************************************
class OrAjax extends OrGui {
  //
  //set_skin_ccs : กำหนดไฟล์ Styles Sheet
  //@param string $file_name ชื่อไฟล์ที่อยู่ใน skin path
  //@return null
  
  function __construct()
  {
 		$this->property('ajax_src','string','../../lib/dojo/dojo/dojo.js');
  }

  //
  //require_tooltip : Class ที่ต้องเรียกใช้
  //@param 
  //@return string ค่าสั่งเรียกใช้ tooltip
  
  function require_tooltip()
  {
 		return $str_ajax = 'dojo.require("dojo.widget.Tooltip");';
  }

  //
  //require_tooltip_css : กำหนดไฟล์ Styles Sheet
  //@param 
  //@return string ค่าสั่งเรียกใช้ tooltip css
  
  function require_tooltip_css()
  {
 		return $str_ajax = 'tooltip.css';
  }

  //
  //require_tooltip : Class ที่ต้องเรียกใช้
  //@param 
  //@return string ค่าสั่งเรียกใช้ tooltip
  
  function require_selectbox()
  {
 		return $str_ajax = 'dojo.require("dojo.widget.ComboBox");';
  }

  //
  //require_tooltip_css : กำหนดไฟล์ Styles Sheet
  //@param 
  //@return string ค่าสั่งเรียกใช้ tooltip css
  
  function require_accordion_css()
  {
 		global $my_cfg;
		return $str_ajax = $my_cfg[skins_path] . 'my_accordion.css';
  }

  //
  //require_accordion : Class ที่ต้องเรียกใช้
  //@param 
  //@return string ค่าสั่งเรียกใช้ accordion
  
  function require_accordion()
  {
		return $str_ajax = 'dojo.require("dojo.widget.AccordionContainer");';
  }

  //
  //require_accordion : Class ที่ต้องเรียกใช้
  //@param 
  //@return string ค่าสั่งเรียกใช้ ContentPane
  
  function require_content_panel()
  {
 		return $str_ajax = 'dojo.require("dojo.widget.ContentPane");';
  }

}


?>
