<?php
//
//OrJS.php - Copyright
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//class OrJs
//Class Java Script Function

class OrJs extends OrGui {
  //
  //@return
  //@access public
  
  function __construct()
  {
		$this->property('js_function','string');
		$this->property('js_src','array');
		$this->property('js_ccs','array');
  }

  // end of member function OrJs
  //
  //คืนค่าเหตุการณ์ที่ Script จะทำงาน
  //
  //@param string event เหตุการณื
  //@return string ค่าเหตุการณ์
  //@access public
  
  function get_script_event($event = "onClick")
  {
		 /*คืนค่า คำสั่งเพื่อเรียกใช้ ตามเหตุการณ์*/
		 return "$event = \"".$this->OP_[js_function]->get()."\"";
  }

}

 // end of OrJs

?>
