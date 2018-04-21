<?php
//
//Created on 10 พ.ย. 2008
//File name is OrPage.php
//@version php5
//@author Suchart Bunhachirat
//@copyright Copyright &copy; 2007, orr

class OrPage extends OrHtml {
  function __construct($title = '' )
  {
 		parent::__construct($title);
 		$this->set_skin('../../Or!Lib/ajax/OrPage.html');
  }

}


?>
