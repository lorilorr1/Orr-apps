<?php
//
//orcontrols.php - Copyright 
//@author Suchart Bunhachirat
//@version php5-2550
//************************************************************************
//
//class OrControls

class OrControls extends OrGui {
  function __construct($id, $name = null, $idx = null )
  {
	  parent:: __construct($id,$name,$idx);
	  $this->property('script','array');
	  $this->property('class_name','string');
	  $this->property('type','string');
	  $this->property('tag','string');
	  $this->property('properties','array');
	  $this->property('style','array');
	  $this->property('auto_post','boolean',false);
	  $this->property('post_value','string');
	  $this->property('clip_name','string');
	  $this->property('set_clip','boolean',false);
	  $this->property('caption','string');
	  $this->property('description','string');
	  $this->property('check_null','boolean',true);
	  $this->property('format_type','string');
	  $this->property('format_option','string');
	  $this->property('title','string');
	  $this->property('read_only','boolean',false);
	  $this->property('db_field','boolean',false);
          $this->property('db_type', 'string'); // กำหนดประเภทข้อมูลตามฟิลด์ข้อมูล
	  $this->property('auto_visible','boolean',true);
	  $this->property('value','string');
	  $this->property('default_value','string');
	  $this->property('is_numeric' ,'boolean', false);
	  $this->property('js_event' ,'string'); //คำสั่ง js script
	  /*$this->OP_[id]->set($id);
	  if($name == null){
		  $this->OP_[name]->set($id);
	  }else{
		  $this->OP_[name]->set($name);
	  }
	  if($idx != null)$this->OP_[idx]->set($idx);*/
  }

  // end of member function OrControls
  //
  //คืนค่าเดิม แสดงที่ controls
  //
  //@return null
  //@access public
  
  function auto_post()
  {
		$val_ = new OrSysvalue();
		$id = $this->OP_[id]->get();
		$name = $this->OP_[name]->get();
		if(!is_null($val_->controls[$id])){
			$val = $val_->controls[$id];
		}else if(!is_null($val_->controls['db_field'][$id])){
			$val = $val_->controls['db_field'][$id];
		}else if(!is_null($val_->filter[$id]) AND substr($name, 0, 10) == 'val_filter'){
			$val = $val_->filter[$id];
		}else if(substr($name, 0, 11) == 'val_compare' ){
			/*ต้องแปลงค่า val_compare[$id] โดยดึงเอา $id มาใข้*/
			$id = substr($name, 12);
			$id = substr($id , 0 , strlen($id) - 1 );
			//echo '<b>compare_id</b> = ' . $id . '<br>';
			$val = $val_->compare[$id];
		}else {
			$val = $val_->message[$id];
		}
		debug_mode(__FILE__ , __LINE__ , $val , $id . ' : Value');
		$this->OP_[post_value]->set($val);
		return null;
  }

  //
  //กำหนดชื่อ clip ที่เก็บข้อมูลใน Session
  //@param string name ชื่อ Clip
  //@return null
  //@access public
  
  function set_clip($name = '')
  {
		if($name != ''){
			$this->OP_[clip_name]->set($name);
		}else{
			$this->OP_[clip_name]->set($this->OP_[id]->get());
		}
		$this->OP_[set_clip]->set(true);
		return null;
  }

  //
  //กำหนดค่า Clip ที่ใช้งาน
  //@param string value
  //@return null
  //@access public
  
  function clip_value($value)
  {
		if($this->OP_[set_clip]->get()){
			$my_clip = new  OrClip($this->OP_[clip_name]->get());
			$my_clip->update_this($value);
		}
		return null;
  }

  //
  //ประเภท format ของข้อมูล
  //@param string type
  //@param string option
  //@return null
  //@access public
  
  function set_format($type, $option = '')
  {
		$this->OP_[format_type]->set($type);
		$this->OP_[format_option]->set($option);
		return null;
  }

  //
  //คือค่ารูปแบบข้อมูลที่กำหนด
  //@return string
  //@access public
  
  function get_format()
  {
	  $my_value = null;
	  if($this->OP_[format_type]->get() != '')
	  {
		  $my_format = new OrFormat($this->OP_[format_type]->get() , $this->OP_[format_option]->get());
		  $my_value = $my_format->get_format($this->OP_[value]->get());
	  }
	  return $my_value;
  }

  //
  //คืนค่า tag properties ที่กำหนดของ control
  //@return mix
  //@access public
  
  function get_properties_tag()
  {
	  if(is_array($this->OP_[properties]->get())){
		  $tag="";
		  $properties=$this->OP_[properties]->get();
		  if($this->OP_[value]->get()!=""){
			  //$properties[set_value]=$this->OP_[value]->get();
			  foreach($properties AS $key => $val){
				  $c=new OrFieldProperty($this->OP_[id]->get(),$key,$this->OP_[idx]->get());
				  $c->OP_[value]->set($val);
				  $tag.=$c->get_tag();
			  }
		  }
	  }
	  return $tag;
  }

  //
  //คืนค่า tag properties ที่กำหนดของ control
  //@return string
  //@access private
  
  function get_id_tag()
  {
	    if($this->OP_[idx]->get() == null){
		  
		  $id = 'id="'.$this->OP_[id]->get().'"';
		  $name='name="'.$this->OP_[name]->get().'"';
		  
	  }else{
		  
		  $id = 'id="'.$this->OP_[id]->get().'['.$this->OP_[idx]->get().']"';
		  $name='name="'.$this->OP_[name]->get().'['.$this->OP_[idx]->get().']"';
	  }
	  $my_value = $id . ' ' . $name;
	  return $my_value;
  }

  //
  //ตรวจสอบค่า auto_post , default_value
  //@param sting $value ค่าที่ต้องการตรวจสอบ
  //@return string ค่าหลังการตรวจสอบ
  
  function get_control_value($value)
  {

		if ($value == null) {
			$value = $this->OP_[value]->get();
		}
		
		if ($value == null AND !is_numeric($value) AND $this->OP_[auto_post]->get()) {
			$this->auto_post();
			$value = $this->OP_[post_value]->get();
		}
		
		if ($value == null AND !is_numeric($value)) {
			$value = $this->OP_[default_value]->get();
		}
		
		$this->OP_[value]->set($value);
		return $value;
  }

  //
  //ตรวจสอบสร้าง Title Tag
  //@param sting $tile ค่าที่ต้องการแสดง Title
  //@return string Tag Title
  
  function get_control_title($title)
  {
		
		if ($title == null) {
			$title = $this->OP_[title]->get();
		}
		
		if ($title != null) {
			$this->OP_[title]->set($title);
			$title = 'title="' . $title . '"';
		}else{
			$title = null;
		}
		return $title;
  }

  //
  //ตรวจสอบสร้าง Control Class Tag
  //@return string Control Class Tag
  
  function get_control_class()
  {
		if ($this->OP_[class_name]->get() == null) {
			$class = null;
		} else {
			$class = 'class="' . $this->OP_[class_name]->get() . '"';
		}
		return $class;
  }

  //
  //Event get_tag()
  //@return mix
  //@access public
  
  function OE_get_tag($param)
  {
	 
	  return $param;
  }

}

 // end of OrControls
?>
