<?php
//
//OrProperty.php - Copyright 
//@author Suchart Bunhachirat
//@version php5 ปี 2550
//************************************************************************
//
//class OrProperty
//Class ต้นแบบของ Property ในระบบ

class OrProperty {
  //
  //ชื่อเรียกของ Property
  //@access private
  
  public $id;

  //
  //@access private
  
  public $value;

  //
  //@access private
  
  public $type;

  //
  //@access private
  
  public $class_name;

  //
  //จำนวนครั้งที่อ่านข้อมูล
  //@access protected
  
  public $value_get =  0;

  //
  //จำนวนที่เขียนข้อมูล
  //@access protected
  
  public $value_set =  0;

  //
  //สถานะข้อมูลถ้ามีการแก้ไขหลังจาก get ล่าสุด แล้วมีการ set ใหม่จะเป็น true
  //@access protected
  
  public $value_update =  false;

  //
  //กำหนดชื่อ ประเภท และค่าเริ่มต้น ของ property
  //@param string id ชื่อ property
  //@param string class_name ชื่อ class ที่กำหนด property
  //@param string type ประเภทข้อมูล
  //@param mix value ค่าของ property
  //@return 
  //@access public
  
  function __construct($id, $class_name, $type = null, $value = null)
  {
	  $this->id = $id;
	  $this->class_name = $class_name;
	  $this->set_type($type,$value);
  }

  // end of member function OrProperty
  //
  //กำหนดค่าที่ต้องการให้ property
  //
  //@param mix value ค่าที่ต้องการกำหนดให้ property
  //@return 
  //@access public
  
  function set($value)
  {
	if(gettype($value) == $this->type ){
		$this->value = $value;
	}else{
		$this->check_value($value);
	}
		$this->value_set +=1;
		$this->value_update = true;
	return null;
  }

  // end of member function set
  //
  //รับค่าปัจจุบันของ property
  //
  //@return mix
  //@access public
  
  function get()
  {
	  $this->value_get += 1;
	  $this->value_update = false;
	  return $this->value;
  }

  // end of member function get
  //
  //ตรวจสอบ และกำหนดประเภทของข้อมูล
  //
  //@param string type ประเภทข้อมูล
  //@param int value ค่าที่กำหนด
  //@return 
  //@access protected
  
  function set_type($type, $value)
  {
	  /*echo "<b>debug</b> ".__FILE__." | ".__LINE__." | type =".$type."<br>";
	  echo "<b>debug</b> ".__FILE__." | ".__LINE__." | value =".$value."<br>";*/
	  if(settype($value , $type)){
		  $this->type=$type;
		  $this->value=$value;
	  }else{
		 $this->type=$type;
		 $this->check_value($value);
		//die("Property  ". $this->id . ' set_type ' . $type . "=type is error!");
	  }
	return null;
  }

  // end of member function set_type
  //
  //ตรวจสอบแก้ไขค่า ของข้อมูลให้ถุกต้องตามประเภทข้อมูล
  //
  //@param mix value ค่าข้อมูลที่ต้องการตรวจสอบแก้ไข
  //@return 
  //@access protected
  
  function check_value($value)
  {
	  switch($this->type){
			case 'integer' :
			if(is_numeric($value) or $value == '' or $value == null){
				$this->value = $value * 1;
			}else{
				die("<b>Error</b> ".__FILE__." | ".__LINE__." | Function check_value Property  ". $this->class_name . ' : ' . $this->id . " set value is ".$value." cannot use type " . $this->type ."<br>");
			}
			break;
			case 'string' :
			if(is_numeric($value) or is_bool($value) or $value == '' or $value == null){
				$this->value = $value; //ถ้าเป็นตัวเลขสามารถปล่อยให้กำหนดค่าได้
			}else{
				die("<b>Error</b> ".__FILE__." | ".__LINE__." | Function check_value Property  ". $this->class_name . ' : ' . $this->id .  " set value is ".$value." cannot use type " . $this->type ."<br>");
			}
			break;
			case 'array' :
			if(is_string($value) OR is_numeric($value) OR $value == ''){
				$this->value = array($value);
			}else{
				die("<b>Error</b> ".__FILE__." | ".__LINE__." | Function check_value Property  ". $this->class_name . ' : ' . $this->id .  " set value is ".$value." cannot use type " . $this->type ."<br>");
			}
			break;
			
			default :
			echo("<b>Error</b> ".__FILE__." | ".__LINE__." | Function check_value Property  ". $this->class_name . ' : ' . $this->id .  " set value is ".$value." cannot use  type " . $this->type ."<br>");
		}
		return null;
  }

  // end of member function check_value
  //
  //ตรวจสอบแก้ไขค่า ของข้อมูลให้ถุกต้องตามประเภทข้อมูล
  //
  //@param mix value ค่าข้อมูลที่ต้องการตรวจสอบแก้ไข
  //@return 
  //@access protected
  
  function check_update()
  {
	  return $this->value_update;
  }

}

 // end of OrProperty
?>
