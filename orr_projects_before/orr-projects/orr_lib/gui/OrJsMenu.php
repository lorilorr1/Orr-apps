<?php
//
//OrJsMenu.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//class OrJs
//Class Java Script Function

class OrJsMenu extends OrJs {
  //
  //กำหนดการสร้าง เมนู
  //@param string File Script
  //@param string CCS Path
  //@return 
  //@access public
  
  function OrJsMenu($jsdomenu_inc, $ccs_path = './Or!Lib/jsDOMenu/themes/office_xp/office_xp.css')
  {
		$this->OrJs();
		$this->OP_[js_ccs]->set(array($ccs_path));
		$this->OP_[js_src]->set(array('./Or!Lib/jsDOMenu/jsdomenu.js' , './Or!Lib/jsDOMenu/jsdomenubar.js' , $jsdomenu_inc));
		$this->OP_[js_function]->set("initjsDOMenu()");
  }

}

 // end of OrJsMenu

?>
