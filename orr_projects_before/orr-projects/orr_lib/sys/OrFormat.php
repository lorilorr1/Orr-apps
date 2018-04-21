<?php
//
//OrFormat.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - php5
//************************************************************************
class OrFormat extends OrObj {
  //Function การกำหนด
  function OrFormat($type, $opt)
  {
		/*เริ่ม กำหนดคุณสมบัติของ Class*/
		$this->property('type','string',$type);
		$this->property('option','string',$opt);
		$this->property('value','string');
		/*จบ กำหนดคุณสมบัติของ Class*/
  }

  //
  //คืนค่าเดิม แสดงที่ controls
  //@param string value ค่าที่กำหนดรูปแบบ
  //@return string รูปแบบที่กำหนด
  //@access public
  
  function get_format($value)
  {
		$val = array();
		switch($this->OP_[type]->get())
		{
			case "currency":
				$this->OP_[value]->set(number_format($value , $this->OP_[option]->get()));
			break;
			
			case "th_date":
			if($value > 0)
			{
				if($this->OP_[option]->get() == "as400")
				{
					$val["th_year"] = substr($value , 0 , 4);
					$val["mon"] = substr($value , 4 , 2);
					$val["mday"] = substr($value , 6 , 2);
					$this->OP_[value]->set($val["mday"] . "/" . $val["mon"] . "/" . $val["th_year"]);
				}else if($this->OP_[option]->get() == "mysql"){
					$my_date = new OrMySqlThDate($value);
					$this->OP_[value]->set($my_date->get_th_str());
				}else if($this->OP_[option]->get() == "mysql_t"){
					$my_date = new OrMySqlThDatetime($value);
					$this->OP_[value]->set($my_date->get_th_str());
				}else{
					die('กรุณาระบุประเภท[ as400 | mysql | mysql_t ' . $this->OP_[type]->get() . ' กรุณาตรวจสอบคำสั่ง');
				}
			}else{
				/*กรณีไม่มีข้อมูลให้แสดงเป็น "-" สุชาติ 8/9/2548*/
				$this->OP_[value]->set("-");
			}
			break;
			
			case "hn":
			if($value > 100){
				$hn = $value / 100;
				$hn = sprintf("%.2f" , $hn);
				$value = str_replace('.' , '-' ,$hn);
				$this->OP_[value]->set($value);
			}
			break;
			
			default:
			$this->OP_[value]->set(sprintf($this->OP_[type]->get() , $value));
		}
		return $this->OP_[value]->get();
  }

}


?>
