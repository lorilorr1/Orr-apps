<?php
//
//orfieldhidden.php - Copyright 
//@author Suchart Bunhachirat
//@version php4
//Here you can write a license for your code, some comments or any other
//information you want to have in your generated code. To to this simply
//configure the "headings" directory in uml to point to a directory
//where you have your heading files.
//
//or you can just replace the contents of this file with your own.
//If you want to do this, this file is located at
//
///usr/share/apps/umbrello/headings/heading.php
//
//-->Code Generators searches for heading files based on the file extension
//i.e. it will look for a file name ending in ".h" to include in C++ header
//files, and for a file name ending in ".java" to include in all generated
//java code.
//If you name the file "heading.<extension>", Code Generator will always
//choose this file even if there are other files with the same extension in the
//directory. If you name the file something else, it must be the only one with that
//extension in the directory to guarantee that Code Generator will choose it.
//
//you can use variables in your heading files which are replaced at generation
//time. possible variables are : author, date, time, filename and filepath.
//just write %variable_name%
//
//This file was generated on %date% at %time%
//The original location of this file is /home/orr/uml-generated-code/orfieldhidden.php
//************************************************************************
//
//class OrFieldHidden

class OrFieldHidden extends OrControls {
  // Aggregations: 
  // Compositions: 
  // Attributes: **
  //
  //
  //@return 
  //@access public
  
 function __construct($id, $name = null, $idx = null) {
        parent:: __construct($id, $name, $idx);
        //$this->OrControls($id ,$name ,$idx);
        $this->OP_[type]->set('hidden');
        $this->OP_[read_only]->set(true);
    }

  // end of member function OrFieldHidden
  //
  //
  //@param mix value ค่าที่กำหนด
  //@return mix
  //@access public
  
  function get_tag($value = null )
  {
	 $param = array();
	 $this->OE_get_tag($param);
	 
	  if($value != null){
		  $this->OP_[value]->set($value);
	  }else if(is_numeric($value)){
		  $this->OP_[value]->set($value);
	  }
	  if($this->OP_[auto_post]->get())$this->auto_post();
	  $id = $this->get_id_tag();
	  $value=$this->OP_[value]->get();
	  
	  $type='type="'.$this->OP_[type]->get().'"';
	  
	  if($value== null  AND !is_numeric($value))$value=$this->OP_[post_value]->get();
	  if($value== null  AND !is_numeric($value))$value=$this->OP_[default_value]->get();
	  
	  $this->clip_value($value);
	  $value='value="'.$value.'"';
	  $my_value = "<input $id $type $value>".$this->get_properties_tag();
	  return $my_value;
  }

}

 // end of OrFieldHidden
?>
