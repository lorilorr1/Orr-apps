<?php
//
//ortextarea.php - Copyright 
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
//The original location of this file is /home/orr/uml-generated-code/ortextarea.php
//************************************************************************
//
//class OrTextarea

class OrTextarea extends OrControls {
  // Aggregations: 
  // Compositions: 
  // Attributes: **
  //
  //
  //@return 
  //@access public
  
  function __construct($id, $name = null, $idx = null) {
        parent:: __construct($id, $name , $idx );
        //$this->OrControls($id, $name, $idx);
        $this->property('rows', 'integer', 5);
        $this->property('cols', 'integer', 50);
        
        $this->OP_[db_type]->set('text');
    }

  // end of member function OrTextarea
  function set_rowcol($rows, $cols)
  {
	  $this->OP_[rows]->set($rows);
	  $this->OP_[cols]->set($cols);
	  return null;
  }

  function get_tag($value = null)
  {
	  if($value != null)$this->OP_[value]->set($value);
	  if($this->OP_[auto_post]->get())$this->auto_post();
	  $id = $this->get_id_tag();
	  $value = $this->OP_[value]->get();
	  $rows = 'rows="' . $this->OP_[rows]->get() . '"';
	  $cols = 'cols="' . $this->OP_[cols]->get() .  '"';
	
	  if($value == "")$value = $this->OP_[post_value]->get();
	  if($value == "")$value = $this->OP_[default_value]->get();
	 
	  if($this->OP_[class_name]->get() == null)
	  {
		  $class = null;
	  }else{
		  $class = 'class="'.$this->OP_[class_name]->get().'"';
	  }
	  $this->clip_value($value);
	  $title = 'title="' . $this->OP_[title]->get() . '"';
	  return $tag= "<textarea $id $class $rows $cols $title>".$value."</textarea>\n";
  }

}

 // end of OrTextarea
?>
