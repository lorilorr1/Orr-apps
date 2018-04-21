<?php
//
//Class สร้าง Table 
//@package    Or!Lib
//@author     Suchart Bunhachirat <suchart_bu@yahoo.com>
//@copyright  1997-2005 The PHP Group
//@license    http://www.php.net/license/3_0.txt  PHP License 3.0
//@version    2550

class OrTable extends OrGui {
  //
  //Cols
  //
  //@access public
  
  var $cols =  array();

  //
  //Rows
  //
  //@access public
  
  var $rows =  array();

  //
  //@param string id
  //@param string name
  //@return null
  //@access public
  
  function OrTable($id, $name = null)
  {
		parent:: __construct($id,$name);
		//$this->property('id','string',$id);
		//$this->property('name','string');
		$this->property('class_name','string');
		$this->property('tag','string');
		$this->property('body','array');
		$this->property('col','string');
		$this->property('align_table','string');
		$this->property('width_table','string');
		
		$this->OP_[id]->set($id);
		if($name == null){
			$this->OP_[name]->set($id);
		}else{
			$this->OP_[name]->set($name);
		}
  }

  //
  //join cell
  //@param integer row  
  //@param integer col
  //@return null
  //@access public
  
  function set_join_cells($cell, $col, $row,$class = null)
  {
		if($class != null)
		{
			$class='class="'.$class.'"';
		}
		$join = 'colspan="' . $col . '" rowspan="' . $row  . '"';
                //$join = 'colspan="' . $col . '"' ;
		$this->cols[] =  "<td $join $class>$cell</td>";
		return null;
  }

  //
  //กำหนด Column ในแต่ละ Row
  //@param string col html tag
  //@param string class HTML Class
  //@return null
  //@access public
  
  function set_col($col = null , $class = null)
  {
		if($class != null)
		{
			$class='class="'.$class.'"';
		}
		$this->cols[] =  "<td $class>$col</td>";
		return null;
  }

  //
  //กำหนด Row ใหม่หลังจากกำหนด Column ครบแล้ว
  //@param string col html tag
  //@param string class HTML Class
  //@return null
  //@access public
  
  function set_row($class = null)
  {
		if($class != null)
		{
			$class='class="'.$class.'"';
		}
		$row = "\n<tr $class>" ;
		foreach($this->cols as $key=>$val)
		{
			$row .= $val;
		}
		$this->rows[] = $row . "</tr>\n";
		$this->cols = array();
		return null;
  }

  function get_tag()
  {
		$id='id="'.$this->OP_[id]->get().'"';
		$name='name="'.$this->OP_[name]->get().'"';
		if($this->OP_[class_name]->get() != null)
		{
			$class='class="'.$this->OP_[class_name]->get().'"';
		}
		if($this->OP_[align_table]->get() != null)
		{
			$align='align="'.$this->OP_[align_table]->get().'"';
		}
		if($this->OP_[width_table]->get() != null)
		{
			$width='width="'.$this->OP_[width_table]->get().'"';
		}
		$tag = array("<table $id $name $class $width $align>\n");
		foreach($this->rows as $key=>$val)
		{
			$tag[]=$val;
		}
		$tag[]="\n</table>";
		return $tag;
  }

}



 ?>
