<?php
//
//Created on 22 พ.ย. 2008
//File name is OrAjax.php
//@author MR.SUCHART BUNHACHIRAT
//@copyright Copyright 2007, orr
//@version php5
//@see OrHtml

class OrAjax extends OrJs {
  //
  //ตัวแปรสำหรับสร้างคำสั่ง dojo.require เพื่อเรียกใช้คลาสใน Dojo
  //Create commend "dojo.require" for use class on Dojo.
  //@access private
  
  private $js_script =  'private';

  //
  //คอนสตรัคเตอร์ เมธอดเริ่มต้นอัตโนมัติของ คลาส เมื่อมีการสร้างออบเจ็ค เพื่อกำหนดค่าเริ่มต้น ของแอตทริบิวต์
  //@access public
  //@return
  
  public function __construct()
  {
		parent :: __construct();
  }

  // เมธอด สร้างคำสั่ง Java Script สำหรับ set_script ของ OrHtml
  //@access public
  //@param  null
  //@return  string
  
  public function get_js_script()
  {

		return $fun_return;
  }

  // เมธอด สร้าง Object
  //@access public
  //@param  null
  //@return  string
  
  public function get_js_script()
  {

		return $fun_return;
  }

}


?>
