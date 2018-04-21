<?php
//
//OrTextbox.php - Copyright 
//@author Suchart Bunhachirat
//@version php4
//************************************************************************
//
//class OrTextbox

class OrTextbox extends OrControls {
  // Aggregations: 
  // Compositions: 
  // Attributes: **
  //
  //Properties
  //$this->property('maxlength','integer');
  //$this->property('size','integer');
  //$this->property('type','string');
  //
  //@return null
  //@access public
  
  function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);
//$this->OrControls($id, $name, $idx);

        $this->property('maxlength', 'integer');
        $this->property('size', 'integer');
        $this->property('password', 'boolean', false);

        $this->OP_[type]->set('text');
        $this->OP_[db_type]->set('text');
    }

    // end of member function OrTextbox
  //
  //กำหนดความกว้าง
  //
  //@param int size ขนาดที่ต้องการแสดง
  //@param int maxlength จำนวนค่าที่รับได้
  //@return null
  //@access public
  
  function set_size($size, $maxlength = 0) {
        $this->OP_[size]->set($size);
        if ($maxlength == 0) {
            $this->OP_[maxlength]->set($size);
        } else if ($maxlength > 0) {
            $this->OP_[maxlength]->set($maxlength);
        }
        return null;
    }

  // end of member function set_size
  //
  //
  //@param mix value ค่าที่กำหนด
  //@return mix
  //@access public
  
  function get_tag($value = null )
  {
	  /*$param = array();
	  $this->OE_get_tag($param);*/
	  
	  if($value != null){
		  $this->OP_[value]->set($value);
	  }else if(is_numeric($value)){
		  $this->OP_[value]->set($value);
	  }
	  
	  if($this->OP_[auto_post]->get())
	  {
		  $this->auto_post();
	  }
	  
	  $id = $this->get_id_tag();
	  $value = $this->OP_[value]->get();
	  $maxlength = $this->OP_[maxlength]->get();
	  $size = $this->OP_[size]->get();
	  $type = 'type="'.$this->OP_[type]->get().'"';
	  
	  if($this->OP_[password]->get())$type='type="password"';
	  if($value == null  AND !is_numeric($value))
	  {
		  $value=$this->OP_[post_value]->get();
	  }
	  
	  if($value == null  AND !is_numeric($value))
	  {
		  $value=$this->OP_[default_value]->get();
	  }
	  
	  if($maxlength == 0)
	  {
		  $maxlength = null ;
	  }else{
		  $maxlength = 'maxlength="'.$maxlength.'"';
	  }
	  
	  if($size == 0)
	  {
		  $size = null;
	  }else{
		  $size = 'size="'.$size.'"';
	  }
	  
	  if($this->OP_[class_name]->get() == null)
	  {
		  $class = null;
	  }else{
		  $class = 'class="'.$this->OP_[class_name]->get().'"';
	  }
	 $this->clip_value($value);
	 $value='value="'.$value.'"';
	 $title = 'title="' . $this->OP_[title]->get() . '"';
	 $my_value = "<input $id $class  $type $maxlength $size $value $title>".$this->get_properties_tag();
	 return $my_value;
  }

}

 // end of OrTextbox
?>
