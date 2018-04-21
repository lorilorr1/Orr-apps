<?php
//
//property.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//class OrObj
//Class เริ่มของการสร้าง Class ใน Or!Lib

class OrObj {
  //
  //@access public
  
  public $class_name;

  //
  //ตัวแปรสำหรับเก็บ Property ของ Object ที่สร้าง
  //@access public
  
  public $OP_ =  array();

  //
  //ตัวแปรสำหรับเก็บ Event ของ Object ที่สร้าง
  //@access public
  
  public $OE_ =  array();

  //
  //
  //@param string class_name    
  //@return 
  //@access public
  //
  //function OrObj( $class_name )
  //{
  //$this->class_name = $class_name;
  //} // end of member function OrObj
  //
  //กำหนด property ของ object ใน class
  //
  //@param string id ชื่อของ property
  //@param string type ประเภทข้อมูล
  //@param mix value ค่าเริ่มต้นของ property
  //@return 
  //@access public
  
  function property($id, $type, $value = null )
  {
	  if(array_key_exists($id,$this->OP_))
	  {
	  	echo "<b>debug</b> ".__FILE__." | ".__LINE__." | Property exists Name ". $id ."<br>";
	  }
	  $this->OP_[$id] = new OrProperty($id  , $this->class_name , $type , $value);
	  return null;
  }

  // end of member function property
  function __clone()
  {
	  foreach ($this->OP_ as $id => $val){
		  $this->OP_[$id] = clone($val);
	  }
  }

  //
  //กำหนด event ของ object ใน class
  //กำหนดคำสั่งเพื่อสั่งให้ทำงาน ตามเหตุการณ์
  //@return 
  //@access public
  
  function event($id)
  {
	  $this->OE_[$id] = new OrProperty($id  , $this->class_name , 'string' );
  }

  // end of member function event
  //
  //ตรวจสอบการกำหนด Object Property คืนค่า true ถ้ามี Property ที่ค้นหา
  //
  //@param object obj Object ที่จะตรวจสอบ
  //@param string property_name
  //@return 
  //@access public
  
  function is_OP($obj, $property_name)
  {
  	$exist=false;
  	if(is_object($obj))
  	{
  		$obj_vars = get_object_vars($obj);
  		foreach ($obj_vars as $name => $o_val)
  		{
  			if($name=="OP_" AND is_array($o_val))
  			{
  				foreach($o_val as $key=>$val)
  				{
  					if($key==$property_name)$exist=true;
  					if($exist)break;
  				}
  			}
			if($exist)break;
  		}
	}
	return $exist;
  }

}

 // end of OrObj

?>
