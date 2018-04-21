<?php
//
//Created on Mar 10, 2007 OrSqlCrossTab.php
//@version php5
//@author Suchart Bunhachirat
//@copyright Copyright &copy; 2007, orr
//Class สำหรับสร้างคำสั่ง SQL เพื่อทำ CrossTab Form

class OrSqlCrossTab extends OrSql {
  function __construct()
  {
		/**
		 * col_field_name : ชื่อ Field ที่ใช้แสดงใน ส่วนคอลัมน์
		 */
		$this->property('col_field_name','string');
		/**
		 * data_field_name : ชื่อ Field ที่ใช้แสดงใน ส่วนข้อมูล
		 */
		$this->property('data_field_name','string');
		/**
		 * data_function : คำสั่ง SQL ที่ใช้คำนวณ
		 */
		$this->property('data_function','string','sum');
		/**
		 * col_caption : คำอธิบายของแต่ละคอลัมน์
		 */
		$this->property('col_caption','array');
		/**
		 * row_total : คำอธิบายของแต่ละคอลัมน์
		 */
		$this->property('row_total','array');
		/**
		 * col_function : คำอธิบายของแต่ละคอลัมน์
		 */
		$this->property('col_function','array');
		$this->property('tab_label','string','col_');
		$this->property('tab_limit','integer',12);
		$this->property('tab_sql','string');
  }

  function get_sql($db, $sql)
  {
		/*สร้างคำสั้ง SQL ของรายงาน Cross Tab */
		$col_caption = array();
		$row_total = array();
		$col_field_name = $this->OP_[col_field_name]->get();
		$data_field_name = $this->OP_[data_field_name]->get();
		$tab_label = $this->OP_[tab_label]->get();
		$data_function = $this->OP_[data_function]->get();
		$limit = $this->OP_[tab_limit]->get();
		//$my_sql = $sql;
		$tab_cmd = new OrSql();
		$sql_cmd = new OrSql($sql);//ตรวจสอบคำสั่ง SQL แปลงเป็น Object
		$sql_tab_select = "DISTINCT `" . $col_field_name . "`"; //คำสั้งหาข้อมูลที่ต้องการแปลงเป็น Field
		$tab_cmd->OP_[cmd_select]->set($sql_tab_select);
		$tab_cmd->OP_[cmd_from]->set($sql_cmd->OP_[cmd_from]->get());
		$tab_cmd->OP_[cmd_where]->set($sql_cmd->OP_[cmd_where]->get());
		$tab_cmd->set_cmd_order("`" . $col_field_name . "`");
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | tab_cmd->get_cmd_sql() =".$tab_cmd->get_cmd_sql()."<br>";
		$db->get_query($tab_cmd->get_cmd_sql());
		if($db->is_error())die($db->show_error());
		$i = 0;
		if($data_function == 'sum'){
			$str_data = '`' .  $data_field_name . '`';
		}else if($data_function == 'count'){
			$str_data = 1 ;
		}
		$str_function = 'sum';
		while($db->get_record()){
			$i ++;
			$id = $tab_label .  $i ;
			$col_caption[$id] = $db->record[$col_field_name];
			$row_total[$id] = $str_function. "(IF(`" . $col_field_name . "` = '" . $db->record[$col_field_name] . "', " . $str_data . " , 0 )) AS `tt_" . $tab_label .  $i . "` ";
			$col_function[$id] = $str_function. "(IF(`" . $col_field_name . "` = '" . $db->record[$col_field_name] . "', " . $str_data . " , 0 ))" ;
			$my_value .= "," . $col_function[$id] . " AS `" .  $id ."`";
			//$my_value .= "," . $str_function. "(IF(`" . $col_field_name . "` = '" . $db->record[$col_field_name] . "', " . $str_data . " , 0 )) AS `" . $tab_label .  $i . "` ";
			if($i > $limit){
				die('Data limit = ' . $limit . ' Cannot Process!');
				break;
			}
		}
		$col_function[$tab_label . '0'] = $str_function. '(' . $str_data . ')' ;
		$my_value .= ',' . $str_function. '(' . $str_data . ') AS `' . $tab_label . '0' . '` ';
		$row_total[$tab_label . '0'] = $str_function. '(' . $str_data . ') AS `' . 'tt_' . $tab_label . '0' . '` ';
		$col_caption[$tab_label .  '0'] = 'รวม';
		unset($db);
		$this->OP_[col_caption]->set($col_caption);
		$this->OP_[row_total]->set($row_total);
		$this->OP_[col_function]->set($col_function);
		//$sql_cmd = new OrSql($my_sql);//ตรวจสอบคำสั่ง SQL แปลงเป็น Object
		$sql_cmd->OP_[cmd_select]->set($sql_cmd->OP_[cmd_select]->get() . $my_value);
		$my_value = $sql_cmd->get_cmd_sql();
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | my_value =".$my_value."<br>";
		$this->OP_[tab_sql]->set($my_value);
		return $my_value;
  }

}


?>
