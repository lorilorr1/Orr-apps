<?php
//
//OrDbForm.php - Copyright 
//@author Suchart Bunhachirat
//@version php5-2550
//***********************************************************************
//
//Class Total Function
//Use In OrDbFormList

class OrDbFormListTotalFunction {
  //
  //Field Name
  //@access public
  
  public $field_name;

  //
  //Field Function
  //@access public
  
  public $field_function;

  function __construct($field_name, $field_function = 'SUM')
  {
		$this->field_name = $field_name;
		$this->field_function = $field_function;
  }

}
//
//Class Database Form List
//คลาส สำหรับสร้างหน้าจอแสดงข้อมูล แบบรายงาน

class OrDbFormList extends OrForm {
  private $total_format_type =  'currency';

  private $total_format_opt =  2;

  //
  //Object ฐานข้อมูล
  //@access public
  
  public $db =  null;

  //
  //Controls function
  //@access public
  
  public $total_controls =  array ();

  //
  //ตารางรายการข้อมูล
  //@access public
  
  public $tbl_list =  null;

  //
  //ข้อมูลในตารางรายการรูปแบบ Array
  //@access public
  
  public $page_data =  array();

  //
  //Total Funtion
  //@access private
  
  private $total_function =  array ();

  //
  //field name ที่ต้องการเปลียนชื่อ
  //@access private
  
  private $filter_name =  array ();

  //
  //default compare ที่ต้องการกำหนด
  //@access private
  
  private $filter_compare =  array ();

  //ดึงข้อมูลจากฐานข้อมูล 
  //@param integer $row_per_page จำนวนรายการที่แสดงในหนึ่งหน้า
  //@return object
  //@access public
  
  function __construct($id, $name = '', $row_per_page = 10)
  {
		/*Constructors*/
		//$this->OrForm($id,$name);
		parent :: __construct($id, $name);
		$this->tbl_list = new OrTable('db_' . $this->OP_[id]->get());
		$this->OP_[db_form]->set(true);

		/*เริ่ม กำหนดคุณสมบัติของ Calss*/
		//$this->property('cmd_sql','string');
		$this->property('cmd_filter', 'string');
		$this->property('cmd_group_filter', 'string');
		$this->property('cmd_order', 'string');
		$this->property('cmd_msg', 'string');
		$this->property('row_per_page', 'integer', $row_per_page);
		$this->property('cur_page', 'integer', 1);
		$this->property('go_page', 'integer', 1);
		$this->property('total_page', 'integer', 0);
		$this->property('total_row', 'integer', 0);
		$this->property('cmd_limit', 'string');
		$this->property('tab_select_total', 'string');
		$this->property('sql_cmd', 'object');
		$this->property('on_load', 'integer', 0);
		$this->property('message', 'string'); //ข้อมูลแจ้งกลับผู้ใช้งาน
		$this->property('on_total', 'boolean', false);
		$this->property('on_click', 'string');

		/*เริ่ม กำหนดเหตุการณ์ของ Calss*/
		$this->event('current_record');
		$this->event('on_load');
		$this->event("on_total"); //เหตุการณ์ขณะประมวลผล Total Rows
		/*จบ กำหนดเหตุการณ์ของ Calss*/
		/*
		กำหนดค่าเริ่มต้นของ Class
		$row_per_page = จำนวนรายการที่ต้องการให้แสดงในหนึ่งหน้า*/
		//$this->OP_[sql_cmd]->set(new sql_cmd());
		return null;
  }

  //ตรวจสอบ กำหนด Object ฐานข้อมูล 
  //@param object obj Object ฐานข้อมูล
  //@return null
  //@access public
  
  function set_db($obj)
  {
		if (is_object($obj)) {
			$this->db = $obj;
		} else {
			die("form->set_db : [$obj] This isn't object");
		}
		return null;
  }

  //fetch_record ดึงข้อมูลตามที่กำหนด
  //@param string cmd_sql คำสั่ง SQL
  //@param string cmd_filter คำสั่ง Filter เพื่อกรองข้อมูล 
  //@param string cmd_msg ข้อความ Message สำหรับแสดง
  //@param boolean reset สั่งเป็น true ถ้าต้องยกเลิกคำสั่งเดิมทั้งหมด
  //@return null
  //@access public
  
  function fetch_record($sql, $obj_filter = '', $cmd_msg = '', $reset = false)
  {
		$my_sec = new OrSec();
		if (get_class($obj_filter) == 'OrSql') {
			$cmd_filter = $obj_filter->OP_[cmd_filter]->get();
			$cmd_group_filter = $obj_filter->OP_[cmd_group_filter]->get();
			$cmd_order = $obj_filter->OP_[cmd_order]->get();
		} else {
			die('fetch_record filter use OrSql Object!');
		}
		$sql_cmd = new OrSql();
		$val_ = new OrSysvalue();
		$sql_cmd->set_cmd_sql($sql);
		$db_event = $val_->db_event;

		if ($db_event[cmd_filter] != "") {
			$this->OP_[cmd_filter]->set(str_replace("#", "'", $db_event[cmd_filter]));
		}

		if ($db_event[cmd_group_filter] != "") {
			$this->OP_[cmd_group_filter]->set(str_replace("#", "'", $db_event[cmd_group_filter]));
		}

		if ($db_event[cmd_msg] != "") {
			$this->OP_[cmd_msg]->set(str_replace("#", "'", $db_event[cmd_msg]));
		}

		if ($db_event[cmd_order] != "") {
			$this->OP_[cmd_order]->set(str_replace("#", "'", $db_event[cmd_order]));
		}

		if ($db_event[cur_page] > 0) {
			if ($db_event[go_page] > 0 AND $db_event[go_page] != $db_event[cur_page]) {
				$this->OP_[cur_page]->set($db_event[go_page]);
				$db_event[navigator] = '';
			} else {
				$this->OP_[cur_page]->set($db_event[cur_page]);
			}
		}
		/*ตรวจสอบเหตุการณ์ On Load*/
		if ($db_event[on_load] == '') {
			$EV_[EV_db_event] = $db_event;
			$this->OE_on_load($EV_);
			$this->OP_[on_load]->set(1);
		} else {
			$this->OP_[on_load]->set($db_event[on_load] + 1);
		}
		$evt_on_load = new OrFieldHidden("evt_form_db[on_load]");
		$this->set_body($evt_on_load->get_tag($this->OP_[on_load]->get()));
		/***************************/
		if ($db_event[total_page] > 0) {
			$this->OP_[total_page]->set($db_event[total_page]);
			$this->OP_[total_row]->set($db_event[total_row]);
		}

		$txt_cmd_filter = new OrFieldHidden("evt_form_db[cmd_filter]");
		$txt_cmd_group_filter = new OrFieldHidden("evt_form_db[cmd_group_filter]");
		$txt_cmd_order = new OrFieldHidden("evt_form_db[cmd_order]");
		$txt_cmd_msg = new OrFieldHidden("evt_form_db[cmd_msg]");

		if ($reset) {
			$this->OP_[cmd_filter]->set("");
			$this->OP_[cmd_group_filter]->set("");
			$this->OP_[cmd_order]->set("");
			$this->OP_[cmd_msg]->set("");
			$this->OP_[total_page]->set(1);
			$this->OP_[total_row]->set(0);
		} else
			if ($cmd_filter != '' OR $cmd_order != '' OR $cmd_group_filter != '') {
				$this->OP_[cmd_filter]->set($cmd_filter);
				$this->set_body($txt_cmd_filter->get_tag(str_replace("'", "#", $this->OP_[cmd_filter]->get())));
				$this->OP_[cmd_group_filter]->set($cmd_group_filter);
				$this->set_body($txt_cmd_group_filter->get_tag(str_replace("'", "#", $this->OP_[cmd_group_filter]->get())));
				$this->OP_[cmd_order]->set($cmd_order);
				$this->set_body($txt_cmd_order->get_tag(str_replace("'", "#", $this->OP_[cmd_order]->get())));
				$this->OP_[cmd_msg]->set($cmd_msg);
				$this->set_body($txt_cmd_msg->get_tag(str_replace("'", "#", $this->OP_[cmd_msg]->get())));
				$db_event[navigator] = 'First';
				$this->OP_[total_page]->set(1);
				$this->OP_[total_row]->set(0);
			} else {
				$this->set_body($txt_cmd_filter->get_tag(str_replace("'", "#", $this->OP_[cmd_filter]->get())));
				$this->set_body($txt_cmd_group_filter->get_tag(str_replace("'", "#", $this->OP_[cmd_group_filter]->get())));
				$this->set_body($txt_cmd_order->get_tag(str_replace("'", "#", $this->OP_[cmd_order]->get())));
				$this->set_body($txt_cmd_msg->get_tag(str_replace("'", "#", $this->OP_[cmd_msg]->get())));
			}

		if ($db_event[row_page] != '')
			$this->OP_[row_per_page]->set($db_event[row_page]);
		if ($db_event[chg_row] != $db_event[row_page]) {
			if ($db_event[chg_row] != '') {
				$this->OP_[row_per_page]->set($db_event[chg_row]);
			}
			$db_event[navigator] = 'First';
			$this->OP_[total_page]->set(1);
			$this->OP_[total_row]->set(0);
		}

		/*เหตุการณ์การกดปุ่มต่างๆ*/
		$this->OP_[on_click]->set($db_event[navigator]);

		switch ($db_event[navigator]) {
			case "First" :
				$this->OP_[go_page]->set(1);

				break;

			case "Next" :
				if ($this->OP_[cur_page]->get() < $this->OP_[total_page]->get()) {
					$this->OP_[go_page]->set($this->OP_[cur_page]->get() + 1);
				} else {
					$this->OP_[go_page]->set($this->OP_[cur_page]->get());
				}

				break;

			case "Prev" :
				if ($this->OP_[cur_page]->get() > 1) {
					$this->OP_[go_page]->set($this->OP_[cur_page]->get() - 1);
				}

				break;

			case "Last" :

				$this->OP_[go_page]->set($this->OP_[total_page]->get());
				$this->OP_[total_page]->set(0);
				break;

			default :
				if ($this->OP_[cur_page]->get() != '') {
					$this->OP_[go_page]->set($this->OP_[cur_page]->get());
				} else {
					$this->OP_[go_page]->set(1);
				}
		}

		$this->OP_[cmd_limit]->set(" LIMIT " . ($this->OP_[go_page]->get() - 1) * $this->OP_[row_per_page]->get() . "," . $this->OP_[row_per_page]->get());
		/*
		 * สร้างคำสั่ง Where
		 */
		if ($this->OP_[cmd_filter]->get() != "") {
			$sql_cmd->set_cmd_where($this->OP_[cmd_filter]->get());
		}
		/*
		 * สร้างคำสั่ง Having กรณีมี Group By
		 */
		if ($this->OP_[cmd_group_filter]->get() != "") {
			$sql_cmd->set_cmd_having($this->OP_[cmd_group_filter]->get());
		}
		debug_mode(__FILE__, __LINE__, $sql_cmd->get_cmd_sql(), 'fetch_record : $sql_cmd');
		$this->set_total_controls($sql_cmd->get_cmd_sql());

		if ($this->OP_[total_page]->get() <= 1) {
			/*จะต้องไม่มีคำสั่ง ORDER ถึงจะมีค่า Total page ไม่ทราบสาเหตุ สุชาติ บุญหชัยรัตน์ 13/4/2547*/
			$count_sql = '';
			if ($sql_cmd->OP_[cmd_group]->get() == '') {
				/*ต้องไม่มีคำสั่ง Group By จึงสามารถใช้คำสีง Count ได้ สุชาติ บุญหชัยรัตน์ 19 /4/2547*/
				$count_sql = 'SELECT count(*) AS `total_row` ';
				$count_sql .= ' FROM ' . $sql_cmd->OP_[cmd_from]->get();
				if ($sql_cmd->OP_[cmd_where]->get() != ''){
					$count_sql .= ' WHERE ' . $sql_cmd->OP_[cmd_where]->get();
				}
			}
			$this->set_total_page($count_sql, $sql_cmd->get_cmd_sql());
		}

		if ($this->OP_[cmd_order]->get() != "") {
			$sql_cmd->set_cmd_order($this->OP_[cmd_order]->get(), ''); //กำหนดการเรียงเป็น '' เพื่อไม่ให้เกิดคำสั่งเรียงซัำ
		}
		debug_mode(__FILE__, __LINE__, $sql_cmd->get_cmd_sql(), 'function fetch_record');
		$this->db->get_query($sql_cmd->get_cmd_sql() . $this->OP_[cmd_limit]->get());
		
		if ($this->db->is_error()) {
			die("<b>ERROR</b> " . __FILE__ . " | " . __LINE__ . " | Description : " . $this->db->show_error());
		}
		$this->OP_[sql_cmd]->set($sql_cmd); //Object SQL หลังการประมวล สุชาติ บุญหชัยรัตน์ 11/5/2547
		//$this->form_fields = $this->get_form_fields();
		return null;
  }

  //กำหนด Fornat Total Value
  //@param string type 
  //@param string opt 
  //@return null
  //@access public
  
  function set_total_format($type, $opt)
  {
                 $this->total_format_type = $type;
                 $this->total_format_opt = $opt;
                 return null;
  }

  //กำหนด Function ส่วน Footer
  //@param string id field_name
  //@param string function Function ค่าเริ่มต้นเป็น SUM
  //@return null
  //@access public
  
  function set_total_function($field_name, $field_function = 'SUM')
  {
		$this->total_controls[$field_function . '_' . $field_name] = new OrLabel($field_function . '_' . $field_name);
		$this->total_controls[$field_function . '_' . $field_name]->set_format($this->total_format_type , $this->total_format_opt);
                $this->total_function[$field_function . '_' . $field_name] = new OrDbFormListTotalFunction($field_name, $field_function);
		return null;
  }

  //กำหนด Function ส่วน Footer
  //@param string id field_name
  //@param string function Function ค่าเริ่มต้นเป็น SUM
  //@return null
  //@access public
  
  function set_total_controls($sql)
  {
		/*ตรวจสอบสร้างคำสั่ง SQL เพื่อสร้างยอดรวม*/
		debug_mode(__FILE__, __LINE__, $sql, 'set_total_controls : $sql');
		$sql_cmd = new OrSql(); // สร้างใหม่เพื่อไม่ให้กระทบค่าเดิม
		$sql_cmd->set_cmd_sql($sql);
		if (is_array($this->total_function)) {
			$db = $this->db;
			$i = 0;
			foreach ($this->total_function AS $id => $obj) {
				$field_name = $this->get_filter_name($obj->field_name);

				if (strpos($field_name, "`") === false AND strpos($field_name, "(") === false) {
					$field_name = '`' . $field_name . '`';
				}

				if ($i == 0) {
					$str_select = $obj->field_function . '(' . $field_name . ') AS `' . $id . '` ';
				} else {
					$str_select .= ' , ' . $obj->field_function . '(' . $field_name . ')  AS `' . $id . '` ';
				}
				$i++;
			}
		}
		if ($str_select != '' AND $this->OP_['tab_select_total']->get() != '') {
			$str_comma = ' , ';
		}

		if ($str_select != '' OR $this->OP_['tab_select_total']->get() != '') {
			$sql_cmd->OP_[cmd_select]->set($str_select . $str_comma . $this->OP_['tab_select_total']->get());
			$sql_cmd->OP_[cmd_group]->set('');
			debug_mode(__FILE__, __LINE__, $sql_cmd->get_cmd_sql(), 'set_total_controls : $sql_cmd');
			$db->get_query($sql_cmd->get_cmd_sql());
			if ($this->db->is_error()) {
				die("gen_total_list_tag had error = " . $db->show_error());
			}
			$db->get_record();
		}
		/**
		* สร้าง Controls ค่าผลรวม
		**/

		foreach ($this->total_controls AS $control_id => $control) {
			$control->OP_[value]->set($db->record[$control_id]);
			debug_mode(__FILE__, __LINE__, $control->OP_[value]->get(), 'ผลของ' . $control_id);
		}
		return null;
  }

  //สร้างรายการข้อมูล 
  //@return null
  //@access public
  
  function get_list_tag()
  {
		$my_table = $this->tbl_list;
		$my_table->OP_[align_table]->set('center');
		$my_table->OP_[class_name]->set('tbl_body');
		$page_col = array();
		foreach ($this->controls AS $control_id => $control) {
			if($control->OP_[db_field]->get()){
				$my_table->set_col($control->OP_[caption]->get(), 'td_' . $control_id);
				$page_col[] = $control->OP_[caption]->get();
			}
		}
		$this->page_data[] = $page_col;
		$my_table->set_row('tr_caption');
		$i = false;
		$idx = 0;
		while ($this->db->get_record()) {
			$EV_[EV_record] = $this->db->record;
			$page_col = array();
			$this->OE_current_record($EV_);
			foreach ($this->controls AS $control_id => $control) {
				if ($control->OP_[db_field]->get()) {
					$control->OP_[id]->set($control_id . '_' . $idx);
					$control->OP_[name]->set('val_controls[db_field][' . $idx . '][' . $control_id . ']');
					$control->OP_[value]->set($this->db->record[$control_id]);
					debug_mode(__FILE__, __LINE__, $control->OP_[value]->get(), 'ค่า' . $control_id);
					$my_table->set_col($control->get_tag(), 'btd_' . $control_id);
					$page_col[] = $this->db->record[$control_id];
				}
			}

			if ($i) {
				$my_table->set_row('tr_body');
				$i = false;
			} else {
				$my_table->set_row('tr_body_color');
				$i = true;
			}
			$this->page_data[] = $page_col;
			$idx++;
		}

		return $my_table->get_tag();
  }

  //สร้างปุ่มสำหรับเลือกรายการข้อมูล
  //@return string
  //@access public
  
  function get_navigator_tag($skin_file = 'Or!Lib/db/list_navigator.html')
  {
		/*รับค่า Skin ของชุดปุ่่ม*/
		$my_value = new OrSkin($skin_file);
		$go_page = new OrTextbox('evt_form_db[go_page]');
		$go_page->set_size(3, 5);
		$go_page->OP_[value]->set($this->OP_[go_page]->get());

		$chg_row = new OrTextbox('evt_form_db[chg_row]');
		$chg_row->set_size(3);
		$chg_row->OP_[default_value]->set($this->OP_[row_per_page]->get());

		$cur_page = new OrFieldHidden('evt_form_db[cur_page]');
		$cur_page->OP_[value]->set($this->OP_[go_page]->get());

		$total_page = new OrFieldHidden("evt_form_db[total_page]");
		$total_page->OP_[value]->set($this->OP_[total_page]->get());

		$row_page = new OrFieldHidden("evt_form_db[row_page]");
		$row_page->OP_[default_value]->set($this->OP_[row_per_page]->get());

		$total_row = new OrFieldHidden("evt_form_db[total_row]");
		$total_row->OP_[value]->set($this->OP_[total_row]->get());

		$hidden_tag = $cur_page->get_tag() . $total_page->get_tag() . $total_row->get_tag() . $row_page->get_tag();

		$my_value->set_skin_tag('go_page', $go_page->get_tag());
		//$my_value->set_skin_tag('chg_row' , $chg_row->get_tag() . ' รายการ จาก ' . $total_row->get_tag() . ' รายการ');
		$my_value->set_skin_tag('chg_row', ' Of ' . $this->OP_[total_page]->get() . ' Pages Show  ' . $chg_row->get_tag() . ' Rows ');
		$my_value->set_skin_tag('description', 'Total ' . $this->OP_[total_row]->get() . ' Rows');

		//$my_value->set_skin_tag('description' , $total_page->get_tag() . " หน้า  " );
		/*
		$txt_cmd_filter=new field_hidden("txt_cmd_filter","evt_list_navigator[cmd_filter]");
		$txt_cmd_order=new field_hidden("txt_cmd_order","evt_list_navigator[cmd_order]");
		$txt_cmd_msg=new field_hidden("txt_cmd_msg","evt_list_navigator[cmd_msg]");
		*/

		$my_value->set_skin_tag('field_hidden', $hidden_tag);
		return $my_value->get_tag();
  }

  function set_total_page($count_sql, $sql)
  {
		/*ปรับปรุงความเร็วการหาจำนวนรายการ สุชาติ บุญหชัยรัตน์ 16/4/2547*/
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>";
		$db_page = $this->db;
		if ($count_sql == '') {
			debug_mode(__FILE__, __LINE__, $sql, 'function set_total_page');
			$db_page->get_query($sql);
			if ($db_page->is_error())
				die("error set_cmd_sql 99 : <br>" . $db_page->show_error());
			$total_row = $db_page->get_total_row();
		} else {
			debug_mode(__FILE__, __LINE__, $count_sql, 'function set_total_page');
			$db_page->get_query($count_sql);
			if ($db_page->is_error()) {
				//echo("@...........");
				$db_page->reset_error();
				debug_mode(__FILE__, __LINE__, $sql, 'function set_total_page');
				$db_page->get_query($sql);
				if ($db_page->is_error())
					die("error set_cmd_sql 99 : <br>" . $db_page->show_error());
				$total_row = $db_page->get_total_row();
			} else {
				/*ค่ารายการที่ได้จาก Function count ที่เร็วที่สุด*/
				$db_page->get_record();
				$total_row = $db_page->record[total_row];
			}
		}

		$total_page = round($total_row / $this->OP_[row_per_page]->get());
		if (($total_page * $this->OP_[row_per_page]->get()) < $total_row) {
			$total_page = $total_page +1;
		}

		$this->OP_[total_page]->set($total_page);
		$this->OP_[total_row]->set($total_row);
		$EV_[EV_sql] = $sql;
		$this->OE_on_total($EV_);
		return null;
  }

  //
  //กำหนดคำสั่งแทน field name
  //@param string $id
  //@param string $name
  //@return null
  //@access public
  
  function set_filter_name($id, $name)
  {
		$this->filter_name[$id] = $name;
		return null;
  }

  //ค่า Field name ที่ต้องการ
  //@return string
  //@access public
  
  function get_filter_name($id)
  {
		if (!is_null($this->filter_name[$id])) {
			$id = $this->filter_name[$id];
		}
		return $id;
  }

  //
  //กำหนดเงื่อนไขเริ่มต้นที่ต้องการ
  //@param string $id
  //@param string $default_value
  //@return null
  //@access public
  
  function set_filter_compare($id, $default_value)
  {
		$this->filter_compare[$id] = $default_value;
		return null;
  }

  //ค่า Default compare ที่ต้องการ
  //@param string $id
  //@return string
  //@access public
  
  function get_filter_compare($id)
  {
		if (!is_null($this->filter_compare[$id])) {
			$default_value = $this->filter_compare[$id];
		}else{
			$default_value = 'LIKE';
		}
		return $default_value;
  }

  //Event on class 
  function OE_current_record($EV_)
  {
		/*$EV_record : array ค่าเหตุการณ์ที่เกิดขึ้น*/
		extract($EV_, EXTR_OVERWRITE);
		eval ($this->OE_[current_record]->get());
		return null;
  }

  function OE_on_load($EV_)
  {
		/*$EV_db_event : array ค่าเหตุการณ์ที่เกิดขึ้น*/
		extract($EV_, EXTR_OVERWRITE);
		eval ($this->OE_[on_load]->get());
		return null;
  }

  function OE_on_total($EV_)
  {
		/*เหตุการณ์ขณะประมวลผล Total row สุชาติ บุญหชัยรัตน์ 10/5/2547*/
		/*$EV_sql = คำสั่ง SQL*/
		extract($EV_, EXTR_OVERWRITE);
		$this->OP_[on_total]->set(true); //ส่งสถานะที่ Property เพื่อใช้งานในเหตุการณ์อื่นๆ
		//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[on_total] =".$this->OP_[on_total]->get()."<br>";
		eval ($this->OE_[on_total]->get());
		return null;
  }

  function __destruct()
  {
		return null;
  }

}


?>
