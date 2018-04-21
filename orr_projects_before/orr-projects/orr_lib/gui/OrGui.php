<?php
//
//orgui.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - php5
//************************************************************************
//
//class OrGui
//Class เริ่มสำหรับกำหนดคุณสมบัติ ร่วมกัน
//Properties
//$this->property('charset','string',$charset);

class OrGui extends OrObj {
  // Aggregations: 
  // Compositions: 
  // Attributes: **
  //
  //
  //@param string charset รหัสภาษาที่ใช้แสดง
  //@return null
  //@access public
  
  function __construct($id, $name = null , $idx = null )
  {
	  $this->property('id','string');
	  $this->property('idx','string');
	  $this->property('name','string');
	  
	  $this->OP_[id]->set($id);
	  if($name == null){
		  $this->OP_[name]->set($id);
	  }else{
		  $this->OP_[name]->set($name);
	  }
	  if($idx != null)$this->OP_[idx]->set($idx);
  }

}

 // end of OrGui
?>
