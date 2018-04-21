<?php
//
//@author Suchart Bunhachirat
//@version php5
//@deprecated version 2 - Feb 17, 2007
//************************************************************************
//
//OrButton : ปุ่มคำสั่ง
//***********************************************************************
class OrButton extends OrControls {
  //
  //
  //@return 
  //@access public
  
  function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);
        //$this->OrControls($id, $name, $idx);
        $this->property('image_source', 'string');
        $this->OP_[type]->set('submit');
    }

  // end of member function OrButton
  //
  //กำหนดให้เป็นปุ่ม Reset
  //
  //@param mix value ค่าที่กำหนด
  //@return null
  //@access public
  
  function set_reset()
  {
	  $this->OP_[type]->set('reset');
	  return null;
  }

  //
  //กำหนดให้เป็นปุ่ม image
  //
  //@param mix value ค่าที่กำหนด
  //@return mix
  //@access public
  
  function set_image($image_source)
  {
	  $this->OP_[image_source]->set($image_source);
	  return null;
  }

  //
  //
  //@param mix value ค่าที่กำหนด
  //@return mix
  //@access public
  
  function get_tag($value = null )
  {
	  if($value != null)
	  {
		  $this->OP_[value]->set($value);
	  }
	  
	  $id = $this->get_id_tag();
	  
	   if($this->OP_[class_name]->get() == null)
	  {
		  $this->OP_[class_name]->set('button');
		   $class = 'class="'.$this->OP_[class_name]->get().'"';
	  }else{
		  $class = 'class="'.$this->OP_[class_name]->get().'"';
	  }
	  
	  $type='type="'.$this->OP_[type]->get().'"';
	  $value='value="' . $this->OP_[value]->get() . '"';
	  if($this->OP_[image_source]->get() == '')
	  {
		  $title = 'title="' . $this->OP_[title]->get() . '"';
		  return "<input $id $class $type $value $title>";
	  }else{
		 $src='src="'.$this->OP_[image_source]->get().'"';
		 $title = 'title="' . $this->OP_[title]->get() . '"';
		 $js_event = $this->OP_[js_event]->get();
		 return "<button $id $type $class $value $title $js_event><img $src></button>";
	  }
  }

}

 // end of OrButton
?>
