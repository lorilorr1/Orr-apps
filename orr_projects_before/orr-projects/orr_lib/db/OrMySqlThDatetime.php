<?php
//
//OrMySqlThDatetime.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//Class OrMySqlThDatetime
//แปลงตรวจสอบวันที่เวลา MySQL กับ วันที่ เวลา ไทย

class OrMySqlThDatetime {
  //จัดการข้อมูลวันที่เวลาไทย กับวันที่เวลาของ Mysql
  var $get_date = array();

  var $is_datetime = false;

  function OrMySqlThDatetime($datetime = "")
  {
		if($datetime!=""){
			if(!$this->get_is_mysql_datetime($datetime))$this->get_is_th_datetime($datetime);
		}
  }

  function get_is_mysql_datetime($datetime)
  {
		/*ตรวจสอบค่าวันที่เวลา รูปแบบ yyyy-mm-dd hh:mm:ss*/
		/*คืนค่า true=ข้อมูล datetime ถูกต้อง*/
		$datetime = trim(substr($datetime , 0 , 19));
		if(preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/",$datetime,$value) > 0){
			if(checkdate($value[2],$value[3],$value[1]) AND $this->check_time($value[4] , $value[5] , $value[6])){
				$this->get_date["year"]=$value[1];
				$this->get_date["mon"]=$value[2];
				$this->get_date["mday"]=$value[3];
				$this->get_date["hours"]=$value[4];
				$this->get_date["minutes"]=$value[5];
				$this->get_date["seconds"]=$value[6];
				return $this->is_datetime=true;
			}else{
				$this->get_date[]=array();
				return $this->is_datetime=false;
			}
		}else{
			$this->get_date[]=array();
			return $this->is_datetime=false;
		}
  }

  function get_is_th_datetime($datetime)
  {
		/*ตรวจสอบค่าวันที่เวลา รูปแบบ dd/mm/bbbb hh:mm:ss*/
		/*คืนค่า true=ข้อมูล datetime ถูกต้อง*/
		$datetime = trim(substr($datetime , 0 , 19));
		if(preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/",$datetime,$value) > 0){
			if(checkdate($value[2],$value[1],$value[3]-543) AND $this->check_time($value[4] , $value[5] , $value[6])){
				$this->get_date["mday"]=$value[1];
				$this->get_date["mon"]=$value[2];
				$this->get_date["year"]=$value[3]-543;
				$this->get_date["th_year"]=$value[3];
				$this->get_date["hours"]=$value[4];
				$this->get_date["minutes"]=$value[5];
				$this->get_date["seconds"]=$value[6];
				return $this->is_datetime=true;
			}else{
				$this->get_date[]=array();
				return $this->is_datetime=false;
			}
		}else{
			$this->get_date[]=array();
			return $this->is_datetime=false;
		}
  }

  function get_th_str($datetime = "")
  {
		if($datetime != '')$this->get_is_mysql_datetime($datetime);
		
		/*คืนค่าวันที่ไทย รูปแบบ 01/01/2545 00:00:00*/
		if($this->is_datetime){
			$th_date=new th_date($this->get_date);
			$this->get_date=$th_date->get_date();
			return $this->get_date["mday"]."/".$this->get_date["mon"]."/".$this->get_date["th_year"]." ".
				$this->get_date["hours"].":".$this->get_date["minutes"].":".$this->get_date["seconds"];
		}else{
			return null;
		}
  }

  function get_mysql_str($datetime = "")
  {
		if($datetime != '')$this->get_is_th_datetime($datetime);
		
		/*คืนค่าวันที่ mysql รูปแบบ 2002-01-01 00:00:00*/
		if($this->is_datetime){
			return $this->get_date["year"]."-".$this->get_date["mon"]."-".$this->get_date["mday"]." ".
				$this->get_date["hours"].":".$this->get_date["minutes"].":".$this->get_date["seconds"];
		}else{
			return  null;
		}
  }

  function check_time($h, $m, $s)
  {
		/*ตรวจสอบค่าเวลาว่าถูกต้องหรือไม่ สุชาติ บุญหชัยรัตน์ 26/01/2547*/
		$val_return = true; 
		if($h < 0 OR $h > 23){
			$val_return = false;
		}else if($m < 0 OR $m > 59){
			$val_return = false;
		}if($s < 0 OR $s > 59){
			$val_return = false;
		}
		return $val_return;
  }

}


 ?>
