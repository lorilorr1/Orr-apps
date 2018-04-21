<?php
//
//OrTextCalendar2.php
//php5
//
//@package    Or!Lib
//@author     Suchart Bunhachirat <suchart_bu@yahoo.com>
//@copyright  1997-2005 The PHP Group
//@license    http://www.php.net/license/3_0.txt  PHP License 3.0
//@version    2550
//
//
//Class ช่องข้อมูลวันที่ เวลา
//@package    Or!Lib
//@author     Suchart Bunhachirat <suchart_bu@yahoo.com>
//@copyright  1997-2005 The PHP Group
//@license    http://www.php.net/license/3_0.txt  PHP License 3.0
//@version    2550

class OrTextCalendar2 extends OrTextCalendar {
  public $calendar2 =  null;

  function __construct($id, $name = null , $idx = null)
  {
		parent:: __construct($id,$name , $idx );
 		$this->calendar2 =  new OrTextCalendar($id . '_II', "val_msg[" . $id . "_II]" , $idx );
		$this->calendar2->auto_post(true);
  }

  //
  //get_tag: html tag
  //@return string html tag
  
  function get_tag($value = null )
  {
		$my_value = parent::get_tag() . ' - ' . $this->calendar2->get_tag();
		return $my_value;
  }

}


?>
