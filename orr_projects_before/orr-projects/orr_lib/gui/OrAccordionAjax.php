<?php
//
//@version php5
//@author Suchart Bunhachirat
//@copyright Copyright &copy; 2007, orr
//Class OrAccordionAjax
//*****************************************************************
class OrAccordionAjax extends OrGui {
  private $panel =  null;

  //
  //__construct : วิธีการทำงานเริ่มต้น
  //@param string
  //@return null
  
  function __construct($id)
  {
 		parent:: __construct($id);
		 /*
		*การกำหนดคุณสมบัติ ของคลาส ใช้คำสั่ง
		* $this->property('ชื่อ' , 'ประเภทข้อมูล' ,'ค่าเริ่มต้น');
		*/
		// $this->property('ajax_src','string','./dojo-0.4.1-ajax/dojo.js');
		/*
		*การกำหนดเหตุการณ์ ของคลาส ใช้คำสั่ง
		* $this->event('ชื่อเหตุการณ์');
		*/
		//$this->event('on_load');
  }

  //
  //__construct : วิธีการทำงานเริ่มต้นของคลาส
  //@param string
  //@return null
  
  function get_tag()
  {
		 $my_value = '<div dojoType="AccordionContainer" labelNodeClass="label" containerNodeClass="accBody" style="border: 2px solid black;" id="main">';
		 foreach($this->panel as $id=>$value){
			 $my_value .= $value;
		 }
		 $my_value .= '</div>';
		 return $my_value;
  }

  //
  //set_panel : กำหนดหน้าใน Panel
  //@param string tag html
  //@return null
  
  function set_panel($label, $tag, $open = null)
  {
		 if(is_array($tag)){
			 foreach($tag as $id=>$value){
				 $my_tag .= $value;
			 }
			 $tag = $my_tag;
		 }
		 
		 if($open){
			 $my_value = '<div dojoType="ContentPane"  open="true" label="' . $label . '" style="overflow: scroll;">' . $tag . '</div>';
		 }else{
			 $my_value = '<div dojoType="ContentPane"  label="' . $label . '" style="overflow: scroll;">' . $tag . '</div>';
		 }
		 
		 $this->panel[$label] = $my_value;
		 return null;
  }

}


 ?>
