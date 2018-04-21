<?php
//
//Created on Mar 4, 2007
//
//To change the template for this generated file go to
//Window - Preferences - PHPeclipse - PHP - Code Templates

class OrImage extends OrControls {
  function __construct($id, $name = null, $idx = null)
  {
 		parent :: __construct($id,$name,$idx);
 		$this->property('width','integer');
		$this->property('hight','integer');
		$this->OP_[read_only]->set(true);
  }

  function get_tag($value = "", $text = "")
  {
 		$value = $this->get_control_value($value);
 		$title = $this->get_control_title($title);
		$class = $this->get_control_class();
		$id = $this->get_id_tag();
		$width=$this->OP_[width]->get();
		$hight=$this->OP_[hight]->get();
		if($width!="")$width='width="'.$width.'"';
		if($hight!="")$hight='hight="'.$hight.'"';
		$text = 'alt="'.$text.'"';
		$value = 'src="'.$value.'"';
 		return "<img $id $class $name $width $hight $value $text>".$this->get_properties_tag();
  }

}


?>
