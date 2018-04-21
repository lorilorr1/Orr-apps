<?php
//
//Created on 6-Sep-06
//
//To change the template for this generated file go to
//Window - Preferences - PHPeclipse - PHP - Code Templates

class OrThdate {
  var $th_date = array();

  function OrThdate($value = 0)
  {
		if($value==0)
			$value=getdate();
		if(checkdate($value["mon"],$value["mday"],$value["year"]))
		return $this->th_date=$value;
  }

  function get_date($format = 0)
  {
		$date=$this->th_date;
		$date["th_year"]=$this->get_year($date["year"]);
		$date["th_weekday"]=$this->get_day($date["wday"],$format);
		$date["th_month"]=$this->get_month($date["mon"],$format);
		//echo __FILE__."--".__LINE__."--".$this->th_date["th_year"]."<br>\n";
		return $this->th_date=$date;
  }

  //แปลงเลขวันเป็น ชื่อวัน
  function get_day($num_day, $format = 0)
  {
		$th_day_0=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
		$th_day_1=array("อา.","จ.","อ.","พ.","พฤ.","ศ.","ส.");
		//echo __FILE__."--".__LINE__."--".$num_day."<br>\n";
		if($format==0){
			return $th_day_0[$num_day];
		}
		else
		{
			return $th_day_1[$num_day];
		}
  }

  //แปลงเลขเดือนเป็น ชื่อเดือน	
  function get_month($num_month, $format = 0)
  {
		$th_month_0=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$th_month_1=array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$num_month-=1;
		if($format==0){
			return $th_month_0[$num_month];
		}
		else
		{
			return $th_month_1[$num_month];
		}
  }

  //แปลงเลขปี ค.ศ. ปี พ.ศ.	
  function get_year($num_year)
  {
		if($num_year>0){
			$num_year+=543;
		}
		return $num_year;
  }

  function get_th_str()
  {
		$this->get_date();
		if(checkdate($this->th_date["mon"],$this->th_date["mday"],$this->th_date["year"]))
		{
			return $this->th_date["mday"]."/".$this->th_date["mon"]."/".$this->th_date["th_year"];
		}
		else
		{
			return null;
		}
  }

}


?>
