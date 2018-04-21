<?php
/******************************************************************
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * Class ของ Or!Config
 *******************************************************************/
/******************************************************************
* Class กลุ่ม Controls ต่างๆ ที่ใช้ในระบบ
*******************************************************************/
class select_ok_cancel extends OrSelectbox {
	/**
 	 * @param string $id Label id
 	 * @param string $name Label name
 	 * @param int $idx integer id array
 	 * @return
 	 **/
 	function __construct($id , $name = null ,$idx = null) {
		parent:: __construct($id,$name,$idx);
		$this->OP_[option]->set(array (
			'0 Ok' => 0,
			'1 Cancel' => 1
		));
 	}
}
?>
