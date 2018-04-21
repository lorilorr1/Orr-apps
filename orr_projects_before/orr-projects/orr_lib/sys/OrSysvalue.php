<?php
//
//OrDbForm.php - Copyright 
//@author Suchart Bunhachirat
//@version php5-2550
//************************************************************************
//
//class OrSysvalue
//Class จัดการข้อมูลค่าต่างๆ ที่ต้องใช้งานร่วมกัน
//

class OrSysvalue {
  // Aggregations: 
  // Compositions: 
  // Attributes: **
  //
  //ค่า controls ที่รับมา
  //@access public
  
  public $controls;

  //
  //ค่า message ที่รับมา
  //@access public
  
  public $message;

  //
  //ค่า Event ที่รับมา
  //@access public
  
  public $filter;

  //
  //ค่า Event ที่รับมา
  //@access public
  
  public $compare;

  //
  //ค่า Event ที่รับมา
  //@access public
  
  public $db_event;

  //
  //
  //@return 
  //@access public
  
  function OrSysvalue()
  {
  	global $val_controls, $val_msg, $val_filter , $val_compare , $evt_form_db, $evt_list_navigator;
  	if(is_array($val_controls))
  	{
  		$this->controls = $val_controls;
	}
	
	if(is_array($val_msg))
  	{
  		$this->message = $val_msg;
	}
	
	if(is_array($val_filter))
  	{
  		$this->filter = $val_filter;
		debug_mode(__FILE__ , __LINE__ , $val_filter , 'val_filter');
	}
	
	if(is_array($val_compare))
  	{
  		$this->compare = $val_compare;
	}
	
	if(is_array($evt_form_db))
  	{
  		$this->db_event = $evt_form_db;
	}
		
  }

}

 // end of OrSysvalue
?>
