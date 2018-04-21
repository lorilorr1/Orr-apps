<?php
//
//OrSql.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//Class SQL

class OrSql extends OrObj {
  function __construct($sql = null)
  {
		
		//$this->property('tbl_header','object');
		//$this->property('header_class','string' , 'tbl_header');
		//$this->property('tbl_body','object');
		//$this->property('body_class','string','tbl_body');
		//$this->property('tbl_footer','object');
		//$this->property('footer_class','string','tbl_footer');
		$this->property('cmd_select','string');
		$this->property('cmd_where','string');
		$this->property('cmd_from','string');
		$this->property('cmd_group','string');
		$this->property('cmd_having','string');
		$this->property('cmd_order','string');
		$this->property('cmd_sql','string');
		$this->property('cmd_filter','string');
		$this->property('cmd_group_filter','string');
		
		if(!is_null($sql))
		{
			$this->set_cmd_sql($sql);
		}
  }

  function get_cmd_sql()
  {
		/*คืนค่าคำสั่ง SQL ที่ถูกต้อง*/
		$value = 'SELECT '. $this->OP_[cmd_select]->get();
		$value .= ' FROM '. $this->OP_[cmd_from]->get();
		if($this->OP_[cmd_where]->get() != '')$value .= ' WHERE ( '. $this->OP_[cmd_where]->get() . ' )';
		if($this->OP_[cmd_group]->get() != '')$value .= ' GROUP BY '.$this->OP_[cmd_group]->get();
		if($this->OP_[cmd_having]->get() != '')$value .= ' HAVING '.$this->OP_[cmd_having]->get();
		if($this->OP_[cmd_order]->get() != '')$value .= ' ORDER BY '.$this->OP_[cmd_order]->get();
		$this->OP_[cmd_sql]->set($value);
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | value =".$value."<br>";
		return $value;
  }

  function set_cmd_sql($sql)
  {
		/*แยกคำสั่ง SQL ตาม properties*/
		$sql = trim($sql);
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>";
		if(strpos(strtolower($sql),'select ') === false){
			die('ไม่พบคำสั่ง SELECT กรุณาตรวจสอบ!');
		}
		$from_pos = strpos(strtolower($sql),' from '); /*หาตำแหน่งคำสั่ง From หลัง Select*/
		if($from_pos === false){
			die('ไม่พบคำสั่ง From กรุณาตรวจสอบ!');
		}
		$this->OP_[cmd_select]->set(substr($sql , 7 , $from_pos - 7));/*ตัดส่วนของคำสั่ง Select*/
		$sql = substr($sql,$from_pos); /*ลบส่วนคำสั่ง Select ใน SQL ออกไป*/
		$where_pos =  strpos(strtolower($sql),' where '); /*หาตำแหน่งคำสั่ง Where หลัง From*/
		/*กำหนดส่วน From*/
		if($where_pos === false){
			if(strpos(strtolower($sql),' group by ') > 0){
				$group_pos =  strpos(strtolower($sql),'group by'); /*หาตำแหน่งคำสั่ง Group By */
				$this->OP_[cmd_from]->set(trim(substr($sql , 6 , $group_pos - 6 )));
				$sql = substr($sql,$group_pos); /*ลบส่วนคำสั่ง From ใน SQL ออกไป*/
			}else if(strpos(strtolower($sql),' order by ') > 0){
				$order_pos =  strpos(strtolower($sql),'order by'); /*หาตำแหน่งคำสั่ง Order By */
				$this->OP_[cmd_from]->set(trim(substr($sql , 6 , $order_pos - 6 )));
				$sql = substr($sql,$order_pos); /*ลบส่วนคำสั่ง From ใน SQL ออกไป*/
			}else{
				$this->OP_[cmd_from]->set(trim(substr($sql , 6 )));
			}
		}else{
			$this->OP_[cmd_from]->set(trim(substr($sql , 6 , $where_pos - 6)));
			$sql = substr($sql,$where_pos); /*ลบส่วนคำสั่ง From ใน SQL ออกไป*/
		}
		/*กำหนดส่วน Where*/
		if($where_pos > 0){
			if(strpos(strtolower($sql),' group by ') > 0){
				$group_pos =  strpos(strtolower($sql),'group by'); /*หาตำแหน่งคำสั่ง Group By */
				$this->OP_[cmd_where]->set(trim(substr($sql , 7 , $group_pos - 7)));
				$sql = substr($sql,$group_pos); /*ลบส่วนคำสั่ง Where ใน SQL ออกไป*/
			}else if(strpos(strtolower($sql),' order by ') > 0){
				$order_pos =  strpos(strtolower($sql),'order by'); /*หาตำแหน่งคำสั่ง Order By */
				$this->OP_[cmd_where]->set(trim(substr($sql , 7 , $order_pos - 7)));
				$sql = substr($sql,$order_pos); /*ลบส่วนคำสั่ง where ใน SQL ออกไป*/
			}else{
				$this->OP_[cmd_where]->set(trim((substr($sql , 7 ))));
			}
		}
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>";
		/*กำหนดส่วน Group By*/
		if($group_pos > 0){
			if(strpos(strtolower($sql),' having ') > 0){
				$having_pos =  strpos(strtolower($sql),'having'); /*หาตำแหน่งคำสั่ง Having */
				$this->OP_[cmd_group]->set(trim(substr($sql , 9 , $having_pos - 9)));
				$sql = substr($sql,$having_pos); 
			}else if(strpos(strtolower($sql),' order by ') > 0){
				$order_pos =  strpos(strtolower($sql),'order by'); /*หาตำแหน่งคำสั่ง Order By */
				$this->OP_[cmd_group]->set(trim(substr($sql , 9 , $order_pos - 9)));
				$sql = substr($sql,$order_pos);
			}else{
				$this->OP_[cmd_group]->set(trim(substr($sql , 9 )));
			}
		}
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>";
		/*
		 * กำหนดส่วน Having
		 */
		 if($having_pos > 0){
			if(strpos(strtolower($sql),' order by ') > 0){
				$order_pos =  strpos(strtolower($sql),'order by'); /*หาตำแหน่งคำสั่ง Order By */
				$this->OP_[cmd_having]->set(trim(substr($sql , 7 , $order_pos - 7)));
				$sql = substr($sql,$order_pos);
			}else{
				$this->OP_[cmd_having]->set(trim(substr($sql , 7 )));
			}
		}
		
		/*กำหนดส่วน Order By*/
		if($order_pos > 0){
			
			$this->OP_[cmd_order]->set(trim(substr($sql , 9 )));
		}
		
		/*echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[cmd_select] =".$this->OP_[cmd_select]->get()."<br>";
		echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[cmd_from] =".$this->OP_[cmd_from]->get()."<br>";
		echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[cmd_where] =".$this->OP_[cmd_where]->get()."<br>";
		echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[cmd_group] =".$this->OP_[cmd_group]->get()."<br>";
		echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[cmd_having] =".$this->OP_[cmd_having]->get()."<br>";
		echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[cmd_order] =".$this->OP_[cmd_order]->get()."<br>";*/
		
		return null;
  }

  function set_cmd_where($cmd_where, $logical = 'AND')
  {
		if($logical != '' AND $this->OP_[cmd_where]->get() != ''){
			$cmd_where = ' ' . $logical . ' ( ' . $cmd_where . ' )';
		}
		$this->OP_[cmd_where]->set( $this->OP_[cmd_where]->get() . $cmd_where );
		return null;
  }

  //
  //set_cmd_order : กำหนด Field ที่ต้องการเรียงลำดับ
  //@param string $cmd_having คำสั่งเงื่อนไขตาม SQL
  //@param string $logical AND/OR
  //@return null
  //@access public
  
  function set_cmd_having($cmd_having, $logical = 'AND')
  {
		if($logical != '' AND $this->OP_[cmd_having]->get() != ''){
			$cmd_having = ' ' . $logical . ' ( ' . $cmd_having . ' )';
		}
		$this->OP_[cmd_having]->set( $this->OP_[cmd_having]->get() . $cmd_having );
		return null;
  }

  //
  //set_cmd_order : กำหนด Field ที่ต้องการเรียงลำดับ
  //@param string $field_name ชื่อฟิลด์ที่ต้องการเรียงลำดับ
  //@param string $sort วิธีการเรียงลำดับปกติเป็น ASC
  //@return null
  //@access public
  
  function set_cmd_order($field_name, $sort = 'ASC')
  {
		$cmd_order = $this->OP_[cmd_order]->get();
		
		if(strpos($field_name , "`") === false){
			$field_name = '`'.$field_name.'`';
		}
		
		if($cmd_order != ''){
			$this->OP_[cmd_order]->set($cmd_order.' , '.$field_name.' '.$sort);
		}else{
			$this->OP_[cmd_order]->set($field_name.' '.$sort);
		}
		return null;
  }

  //function set_cmd_filter
  //ใช้เพื่อสร้างคำสั่ง filter ข้อมูล
  //@param string field_name ชื่อ field ที่กำหนดเงื่อนไข
  //@param string compaer เงื่อนไข = <> > < LIKE เป็นต้น
  //@param string value ค่าที่เปรียบเทียบ
  //@param string logical [option] ถ้าไม่กำหนดจะเป็น AND
  //@param bool new filter เพิ่มกลุ่ม filter ใหม่ เป็น True
  //@return null
  //@access public
  
  function set_cmd_filter($field_name, $compare, $value, $logical = 'AND' , $new_filter = false)
  {
		$cmd_filter = $this->OP_[cmd_filter]->get();
		$value = str_replace("*" , "%" , $value );
		if(strpos($field_name , "`") === false AND strpos($field_name , "(") === false){
			$field_name = '`'.$field_name.'`';
		}
		if(strtolower($compare) == 'like')
		{
			$value = $this->get_like_filter($value);
		}else if(strtolower($compare) == 'between'){
			$value = $this->get_between_filter($value);
		}else if(strtolower($compare) == 'in')
		{
			$value = "(" . $this->get_in_filter($value) . ")" ;
		}else{
			$value = "'" . $this->get_filter_value($value) . "'" ;
		}
		if($logical != '' AND $cmd_filter != ''){
			if(!$new_filter){
				$cmd_filter = $cmd_filter.' '.$logical;
			}else{
				$cmd_filter = $cmd_filter.' ) '.$logical.' ( ';
			}
		}
			$this->OP_[cmd_filter]->set($cmd_filter.' '.$field_name.' '.$compare.' '.$value);
		return null;
  }

  //function set_cmd_filter_group
  //ใช้เพื่อสร้างคำสั่ง filter ข้อมูล ในคำสั่ง Having
  //@param string field_name ชื่อ field ที่กำหนดเงื่อนไข
  //@param string compaer เงื่อนไข = <> > < LIKE เป็นต้น
  //@param string value ค่าที่เปรียบเทียบ
  //@param string logical [option] ถ้าไม่กำหนดจะเป็น AND
  //@return null
  //@access public
  
  function set_cmd_group_filter($field_name, $compare, $value, $logical = 'AND')
  {
		$cmd_filter = $this->OP_[cmd_group_filter]->get();
		$value = str_replace("*" , "%" , $value );
		if(strpos($field_name , "`") === false AND strpos($field_name , "(") === false){
			$field_name = '`'.$field_name.'`';
		}
		if(strtolower($compare) == 'like')
		{
			$value = $this->get_like_filter($value);
		}else if(strtolower($compare) == 'between'){
			$value = $this->get_between_filter($value);
		}else if(strtolower($compare) == 'in')
		{
			$value = "(" . $this->get_in_filter($value) . ")" ;
		}else{
			$value = "'" . $this->get_filter_value($value) . "'" ;
		}
		if($logical != '' AND $cmd_filter != ''){
			$cmd_filter = $cmd_filter.' '.$logical;
		}
		$this->OP_[cmd_group_filter]->set($cmd_filter.' '.$field_name.' '.$compare.' '.$value);
		return null;
  }

  //function get_like_filter
  //แปลงรูปแบบข้อมูลให้เหมาะสมกับการใช้ LIKE ค้นหาข้อมูล
  //โดยใส่ % ให้กับข้อมูล
  //@param string $str_filter คำที่ใช้ค้นหา
  //@return null
  //@access public
  
  function get_like_filter($str_filter)
  {
		/*ชุดคำสั่งตรวจสอบ รูปแบบการคันหาข้อมูล กรณีใส่ ' ' หมายถึงค้นหาอย่างกำหนดตามเงื่อนไขในเครื่องหมาย */
		/*แต่ถ้าไม่กำหนดมาให้ค้นหา บ้างส่วนของข้อมูลอย่างอัตโนมัติ สุชาติ บุญหชัยรัตน์ 23/8/2547 */
		$close_pos = strrpos($str_filter , "'");
		if(strstr($str_filter, "'") AND $close_pos > 1){
			$str_filter = substr($str_filter, 1 , $close_pos - 1);
			$str_filter = $this->get_filter_value($str_filter);
		}else{
			$str_filter = $this->get_filter_value($str_filter);
			$str_filter = "'%" . $str_filter . "%'" ;
		}
		return $str_filter;
  }

  //function get_between_filter
  //แปลงรูปแบบข้อมูลให้เหมาะสมกับการใช้ BETWEEN ค้นหาข้อมูล
  //โดยใส่ AND ให้กับข้อมูล
  //@param string $str_filter คำที่ใช้ค้นหา
  //@return null
  //@access public
  
  function get_between_filter($str_filter)
  {
		$str = explode(' ' , $str_filter);
		if($str[1] == '-' OR $str[1] == '')
		{
			$str[1] = 'AND' ;
		}
		
		$str_filter = "'" . $this->get_filter_value($str[0]) . "' " . $str[1] . " '" . $this->get_filter_value($str[2]) . "'" ;
		
		return $str_filter;
  }

  //function set_in_filter
  //แปลงรูปแบบข้อมูลให้เหมาะสมกับการใช้ BETWEEN ค้นหาข้อมูล
  //โดยใส่ AND ให้กับข้อมูล
  //@param string $str_filter คำที่ใช้ค้นหา
  //@return null
  //@access public
  
  function get_in_filter($str_filter)
  {
		$str = explode(' ' , $str_filter);
		$str_filter = "'" ;
		foreach($str AS $id => $value)
		{
			$str_filter .= $this->get_filter_value($value) . "' , '" ;
		}
		$len = strlen($str_filter) - 3 ;
		$str_filter = substr($str_filter , 0 , $len) ;
		return $str_filter;
  }

  //function set_in_filter
  //ตรวจสอบแปลงรูปแบบข้อมูล ให้ถูกต้อง ตามคำสั่ง SQL
  //@param string $str_filter คำที่ใช้ค้นหา
  //@return string ค่าที่ตรวจสอบแก้ไข
  //@access public
  
  function get_filter_value($value)
  {
		if(!is_numeric($value)){
			/*ตรวจสอบการเป็นข้อมูลวันที่เวลาไทยหรือไม่*/
			$mysql_th_datetime = new OrMySqlThDatetime($value);
			if($mysql_th_datetime->is_datetime){
				return $mysql_th_datetime->get_mysql_str();
			}else{
				$mysql_th_date = new OrMySqlThDate($value);
				if($mysql_th_date->is_date){
					return $mysql_th_date->get_mysql_str();
				}else{
					return AddSlashes($value);
				}
			}
		}else{
			return AddSlashes($value); // แก้ไขกรณีบันทึกเลข ทศนิยม สุชาติ บุญหชัยรัตน์  14/10/2547
		}
  }

}


 ?>
