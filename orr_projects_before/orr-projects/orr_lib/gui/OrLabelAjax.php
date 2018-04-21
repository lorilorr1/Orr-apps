<?php
//
//Created on Mar 3, 2007
//
//To change the template for this generated file go to
//Window - Preferences - PHPeclipse - PHP - Code Templates

class OrLabelAjax extends OrLabel {
  private $tooltip_url =  null;

  private $tooltip_style =  'width: 300px;';

  //
  //@param string $id Label id
  //@param string $name Label name
  //@param int $idx integer id array
  //@return
  
  function __construct($id, $name = null , $idx = null)
  {
		parent:: __construct($id,$name,$idx);
  }

  //
  //Fucntion สำหรับกำหนดเอกสารที่ต้องการแสดงใน Tooltip
  //@param string $url html url
  //@return null
  
  function set_ajax_tooltip($url, $style = null)
  {
 		$this->tooltip_url = $url;
 		if(!is_null($style)){
 			$this->tooltip_style = $style;
 		}
 		return null;
  }

  //
  //สร้าง Tag Tooltip
  //@param string $connectId Span Id
  //@return string Tag Ajax Tooltip
  
  function get_ajax_tooltip($connectId)
  {
 		if(!is_null($this->tooltip_url)){
 			$str_ajax = '<span dojoType="tooltip" connectId="'. $connectId . '" href="'. $this->tooltip_url . '" executeScripts="true" style="' . $this->tooltip_style . '"></span>';
 		}
 		return $str_ajax;
  }

  function get_tag($value = null, $title = null)
  {

		$value = $this->get_control_value($value);
		$text = $this->get_control_text($value);
		$title = $this->get_control_title($title);
		$class = $this->get_control_class();
		$id = $this->get_id_tag();
		$field = new OrFieldHidden($this->OP_[id]->get(), $this->OP_[name]->get());
		$span_id = 'id="label_' . $this->OP_[id]->get() . '"';
		$ajax_tooltip = $this->get_ajax_tooltip('label_' . $this->OP_[id]->get());
		return "<SPAN $span_id $title $class>" . $text . "</SPAN>" . $ajax_tooltip . $field->get_tag($value) . $this->get_properties_tag();
  }

}


?>
