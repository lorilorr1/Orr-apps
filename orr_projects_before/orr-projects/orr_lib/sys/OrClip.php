<?php
//
//OrClip.php - Copyright
//Class สร้างตัวแปร Session ส่งระหว่างโปรแกรม 
//@author Suchart Bunhachirat
//@version php4 - php5
//************************************************************************
class OrClip extends OrObj {
  //
  //
  //@param string name ชื่อ clip
  //@param string type ประเภทข้อมูล
  //@access public
  
  function __construct($name, $type = 'string')
  {
		global $my_clip;
		/*เริ่ม กำหนดคุณสมบัติของ Class*/
		$this->property('name' , 'string' , $name);
		$this->property('value' , $type);
		/*จบ กำหนดคุณสมบัติของ Class*/
		
		$this->use_this($name);
  }

  //
  //กำหนด Clip ที่ใช้งาน
  //@param string name ชื่อ clip
  //@access public
  //@return null
  
  function use_this($name)
  {
		global $my_clip;
		$this->OP_[name]->set($name);
		$this->OP_[value]->set($my_clip[$name]);
		return null;
  }

  //
  //update ค่า Clip ที่ใช้งาน
  //@param string value ค่าที่ Update
  //@access public
  //@return null
  
  function update_this($value)
  {
		global $my_clip;
		$this->OP_[value]->set($value);
		$my_clip[$this->OP_[name]->get()]=$value;
		return null;
  }

  //
  //delete ค่า Clip ที่ใช้งาน
  //@access public
  //@return null
  
  function delete_this()
  {
		global $my_clip;
		$this->OP_[value]->set(null);
		$my_clip[$this->OP_[name]->get()]=null;
		return null;
  }

}


?>
