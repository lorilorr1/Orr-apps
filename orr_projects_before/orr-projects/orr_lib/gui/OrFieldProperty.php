<?php
//
//orfieldproperty.php - Copyright 
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
//The original location of this file is /home/orr/uml-generated-code/orfieldproperty.php
//************************************************************************
//
//class OrFieldProperty

class OrFieldProperty extends OrControls {
  // Aggregations: 
  // Compositions: 
  // Attributes: **
  //
  //
  //@param string id    * @param string property    * @param int idx    * @return null
  //@access public
  
  function OrFieldProperty($id, $property, $idx = null )
  {
	  if($idx != ""){
		  $name="val_controls[properties][$id][$idx][$property]";
	  }else{
		  $name="val_controls[properties][$id][$property]";
	  }
	  $this->OrControls($id,$name,$idx);
  }

  // end of member function OrFieldProperty
  //
  //
  //@return mix
  //@access public
  
  function get_tag()
  {
	  $id = 'id="'.$this->OP_[id]->get().'"';
	  $name='name="'.$this->OP_[name]->get().'"';
	  $value='value="'.$this->OP_[value]->get().'"';
	  $type='type="hidden"';
	  $my_value = "<input $id $name $type $value>";
	  return $my_value;
  }

}

 // end of OrFieldProperty
?>
