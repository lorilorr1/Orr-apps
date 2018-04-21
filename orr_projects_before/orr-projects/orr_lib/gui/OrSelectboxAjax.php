<?php
//
//OrSelectboxAjax.php - Copyright 
//@author Suchart Bunhachirat
//@version php5-2550
//************************************************************************
class OrSelectboxAjax extends OrSelectbox {
  //
  //@param string $id Label id
  //@param string $name Label name
  //@param int $idx integer id array
  //@return
  
  function __construct($id, $name = null , $idx = null)
  {
		parent:: __construct($id,$name,$idx);
		$this->use_ajax = true;
  }

}


 ?>
