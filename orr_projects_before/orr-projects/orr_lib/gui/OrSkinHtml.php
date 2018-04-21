<?php
//
//@version php5
//@author Suchart Bunhachirat
//@copyright Copyright &copy; 2007, orr
//Class สร้างไฟล์ HTML
//*****************************************************************
class OrSkinHtml extends OrObj {
  //
  //skin for create HTML
  //@access public
  
  public $html;

  private $meta =  array();

  private $body =  array();

  private $script =  array();

  private $script_src =  array();

  private $ccs_src =  array();

  private $style =  array();

  //
  //__construct : วิธีการทำงานเริ่มต้นของคลาส
  //@param string title Page Title
  //@param string
  //@return null
  
  function __construct($title = '' )
  {
 		global $my_cfg;
                 /*
		*การกำหนดคุณสมบัติ ของคลาส ใช้คำสั่ง
		* $this->property('ชื่อ' , 'ประเภทข้อมูล' ,'ค่าเริ่มต้น');
		*/
		$this->property('title','string',$title);
		//$this->property('body','array');
		//$this->property('script','array');
		//$this->property('script_src','array');
		//$this->property('ccs_src','array');
		//$this->property('meta','array');
		//$this->property('style','array');
		$this->property('script_event_body','string');
		/*
		*การกำหนดเหตุการณ์ ของคลาส ใช้คำสั่ง
		* $this->event('ชื่อเหตุการณ์');
		*/
			//$this->event('on_load');
		/**
                *กำหนด skin ที่ใช้สร้าง HTML
		**/
		//$skin = $my_cfg[skins_path] . 'OrSkinHtml.html';
                $skin = '../Or!Lib/gui/OrSkinHtml.html';
                $this->html = new OrSkin($skin);
  }

  //
  //Head meta ต่างๆ ใน HTML HEADER
  //@param string tag คำสั่ง meta เป็น string
  //@return null
  //@access public
  
  function set_meta($tag)
  {
                if(!is_array($tag)){
                        $tag = array($tag);
                }
                $this->meta = array_merge($this->meta , $tag);
                return null;
  }

  // end of member function set_meta
  //
  //คำสั่ง Script ต่างๆ ใน HTML HEADER
  //@param mix tag คำสั่ง Script ที่ต้องการแสดง สามารถส่งเป็น string หรือ array
  //@return null
  //@access public
  
  function set_script($tag)
  {
                if(!is_array($tag)){
                        $tag = array($tag);
                }
                $this->script = array_merge($this->script , $tag);
                return null;
  }

  // end of member function set_script
  //
  //คำสั่ง Script ต่างๆ ใน HTML HEADER
  //@param mix tag คำสั่ง Script ที่ต้องการแสดง สามารถส่งเป็น string หรือ array
  //@return null
  //@access public
  
  function set_script_src($tag)
  {
                if(!is_array($tag)){
                        $tag = array($tag);
                }
                $this->script_src = array_merge($this->script_src , $tag);
                return null;
  }

  // end of member function set_script
  //
  //คำสั่ง Script ต่างๆ ใน HTML HEADER
  //@param mix tag คำสั่ง Script ที่ต้องการแสดง สามารถส่งเป็น string หรือ array
  //@return null
  //@access public
  
  function set_ccs_src($tag)
  {
                if(!is_array($tag)){
                        $tag = array($tag);
                }
                $this->ccs_src = array_merge($this->ccs_src , $tag);
                return null;
  }

  // end of member function set_script
  //
  //คำสั่ง Script ต่างๆ ใน HTML HEADER
  //@param mix tag คำสั่ง Script ที่ต้องการแสดง สามารถส่งเป็น string หรือ array
  //@return null
  //@access public
  
  function set_style($tag)
  {
                if(!is_array($tag)){
                        $tag = array($tag);
                }
                $this->style = array_merge($this->style , $tag);
                return null;
  }

  // end of member function set_script
  //
  //กำหนดข้อมูลที่แสดงใน HTML page
  //@param mix tag ค่า tag html ที่ต้องการแสดง สามารถส่งเป็น string หรือ array
  //@return null
  //@access public
  
  function set_body($tag)
  {
                if(!is_array($tag)){
                        $tag = array($tag);
                }
                $this->body = array_merge($this->body,$tag);
                return null;
  }

  // end of member function set_body
  //
  //คืนค่า Tag ส่วน meta
  //@return string
  
  function get_meta()
  {
                foreach($this->meta as $line){
                        $my_value .= "<meta " . $line . ">\n";
                }
                return $my_value;
  }

  //
  //คืนค่า Tag ส่วน script source
  //@return string
  
  function get_script_src()
  {
                foreach($this->script_src as $line){
                        $my_value .= "<script language=\"JavaScript\" src=\"" .  $line ."\"></script>\n";
                }
                return $my_value;
  }

  //
  //คืนค่า Tag ส่วน script
  //@return string
  
  function get_script()
  {
                foreach($this->script as $line){
                        $my_value .= $line ;
                }
                return $my_value;
  }

  //
  //คืนค่า Tag ส่วน ccs source
  //@return string
  
  function get_ccs_src()
  {
                foreach($this->ccs_src as $line){
                        $my_value .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" .  $line ."\">\n";
                }
                return $my_value;
  }

  //
  //คืนค่า Tag ส่วน script
  //@return string
  
  function get_style()
  {
                foreach($this->style as $line){
                        $my_value .= $line ;
                }
                return $my_value;
  }

  //
  //คืนค่า Tag ส่วน body
  //@return string
  
  function get_body()
  {
                foreach($this->body as $line){
                        $my_value .= $line ;
                }
                return $my_value;
  }

  //
  //คำสั่งแสดง page html
  //@return null
  //@access public
  
  function show()
  {
                 $this->html->set_skin_tag('head_meta',$this->get_meta());
                 $this->html->set_skin_tag('head_title',$this->OP_[title]->get());
                 $this->html->set_skin_tag('head_script_src',$this->get_script_src());
                 $this->html->set_skin_tag('head_script',$this->get_script());
                 $this->html->set_skin_tag('head_style_src',$this->get_ccs_src());
                 $this->html->set_skin_tag('head_style',$this->get_style());
                 $this->html->set_skin_tag('body',$this->get_body());
                 //$this->html->get_tag();
                  foreach($this->html->get_tag() as $line){
                          echo $line;
                  }
  }

}


 ?>
