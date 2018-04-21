<?php
//
//OrMySqlThDate.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//Class OrMySqlThDate
//แปลงตรวจสอบข้อมูลวันที่ MySql กับ วันที่ไทย

class OrMysqlThDate extends OrObj {
  //จัดการข้อมูลวันที่เวลาไทย กับวันที่ของ Mysql
  var $get_date = array();

  var $is_date = false;

  function OrMysqlThDate($date = "")
  {
		if($date!=""){
			if(!$this->get_is_mysql_date($date))$this->get_is_th_date($date);
		}
  }

  function get_is_mysql_date($date)
  {
		/*ตรวจสอบค่าวันที่เวลา รูปแบบ yyyy-mm-dd*/
		/*คืนค่า true=ข้อมูล date ถูกต้อง*/
		$date = trim(substr($date , 0 , 19));
		if(preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/",$date,$value) > 0){
			if(checkdate($value[2],$value[3],$value[1])){
				$this->get_date["year"]=$value[1];
				$this->get_date["mon"]=$value[2];
				$this->get_date["mday"]=$value[3];
				$this->get_date["th_year"]=$value[1]+543;/*11/6/2547 แก้ไชปัญหาไม่มีค่า พ.ศ.*/
				return $this->is_date=true;
			}else{
				$this->get_date[]=array();
				return $this->is_date=false;
			}
		}else{
			$this->get_date[]=array();
			return $this->is_date=false;
		}
  }

  function get_is_th_date($date)
  {
		/*ตรวจสอบค่าวันที่เวลา รูปแบบ dd/mm/bbbb*/
		/*คืนค่า true=ข้อมูล date ถูกต้อง*/
		$date = trim(substr($date , 0 , 10));
		if(preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/",$date,$value) > 0){
			if(checkdate($value[2],$value[1],$value[3]-543)){
				$this->get_date["mday"]=$value[1];
				$this->get_date["mon"]=$value[2];
				$this->get_date["year"]=$value[3]-543;
				$this->get_date["th_year"]=$value[3];
				return $this->is_date=true;
			}else{
				$this->get_date[]=array();
				return $this->is_date=false;
			}
		}else{
			$this->get_date[]=array();
			return $this->is_date=false;
		}
  }

  function get_th_str($date = "")
  {
		if($date != '')$this->get_is_mysql_date($date);
		
		/*คืนค่าวันที่ไทย รูปแบบ 01/01/2545*/
		if($this->is_date){
			$th_date=new OrThdate($this->get_date);
			$this->get_date=$th_date->get_date();
			return $this->get_date["mday"]."/".$this->get_date["mon"]."/".$this->get_date["th_year"];
		}else{
			return null;
		}
  }

  function get_mysql_str($date = "")
  {
		if($date != ""){
			$this->get_is_th_date($date);
		}
		/*คืนค่าวันที่ mysql รูปแบบ 2002-01-01*/
		if($this->is_date){
			return $this->get_date["year"]."-".$this->get_date["mon"]."-".$this->get_date["mday"];
		}else{
			return  null;
		}
  }

  function get_as400_str($date = "")
  {
	/*คืนค่าวันที่ AS400 ปีพ.ศ. เดือน วัน สุชาติ  บุญหชัยรัตน์ 30/3/2547*/
		if($date != ""){
			$this->get_is_th_date($date);
		}
		/*คืนค่าวันที่ mysql รูปแบบ 25470101*/
		if($this->is_date){
			return $this->get_date["th_year"].sprintf( "%02d", $this->get_date["mon"]).sprintf( "%02d",$this->get_date["mday"]);
		}else{
			return  null;
		}
  }

}


 ?>
