<?php
//
//OrJsCalendar.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//class OrJs
//Class Java Script Function

class OrJsCalendar extends OrJs {
  //
  //กำหนดการใช้ Calendar
  //@param string CCS Path
  //@return 
  //@access public
  
  function OrJsCalendar($ccs_path = './skins/all/calendar.css')
  {
		$this->OrJs();
		$this->OP_[js_ccs]->set(array($ccs_path));
		$this->OP_[js_src]->set(array('../../lib/calendar/calendar.js'));
  }

}

 // end of OrJsCalendar

?>
