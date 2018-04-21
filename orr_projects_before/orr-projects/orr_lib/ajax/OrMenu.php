<?php
//
//Created on 22 พ.ย. 2008
//File name is OrMenu.php
//@author MR.SUCHART BUNHACHIRAT
//@copyright Copyright 2007, orr
//@version php5
//@see Bouml

class OrMenu extends OrJs {
  //
  //กำหนด
  //@access public
  //@return
  
  public function __construct()
  {
  	parent::__construct();
  	$dojo_request = 'dojo.require("dijit.form.Button");dojo.require("dijit.Menu");';
  	$this->OP_[js_function]->set($dojo_request);
  }

  public function set_item()
  {

  }

}


?>
