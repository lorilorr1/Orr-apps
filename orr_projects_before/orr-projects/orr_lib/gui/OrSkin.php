<?php
//
//OrSkin.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - php5
//************************************************************************
//
//class OrSkin
//สร้างรูปแบบหน้าจอ จากไฟล์ HTML ที่เป็นต้นแบบ
//Properties
//
//property('skin_src','string') path of file
//property('skin_tag','array') skin tag name

class OrSkin extends OrGui {
  //
  //กำหนดที่เก็บไฟล์ต้นแบบ
  //
  //@param string skin_src path of file
  //@return null
  //@access public
  
  function OrSkin($skin_src)
  {
   	
   	$this->property('skin_src','string',$skin_src);
	$this->property('skin_tag','array');
	
  }

  //
  //
  //กำหนด tag html ที่ต้องการใส่แทนในจุดที่กำหนด
  //ใช้แทน set_skin
  //
  //@param string name skin tag name
  //@param mix tag html tag
  //@return null
  //@access public
  
  function set_skin_tag($name, $tag)
  {
   	if(is_array($tag))
   	{
   		foreach($tag as $key=>$val)
   		{
   			$skin_tag .= $val;
   		}
	}else{
			$skin_tag = $tag;
	}
		
	$this->OP_[skin_tag]->set(array_merge($this->OP_[skin_tag]->get() , array($name => $skin_tag) ) );
	return null;
  }

  //
  //
  //คืนค่า tag html
  //
  //@return null
  //@access public
  
  function get_tag()
  {
   	//echo "<b>debug</b> ".__FILE__." | ".__LINE__." | skin_src =".$this->OP_[skin_src]->get()."<br>";
   	if(!fopen($this->OP_[skin_src]->get() , "r"))die('failed to open skin: No such file or directory in ' . $this->OP_[skin_src]->get());
	$fp = fopen($this->OP_[skin_src]->get() , "r");
	$skins = $this->OP_[skin_tag]->get();
	$skin_tag = array();
	while(!feof($fp))
	{
		$line = fgets($fp);
		foreach($skins as $key=>$val)
		{
			$skin_key ='/<!--Or_Skin\[' . $key . '\]-->/';
			//$line = ereg_replace($skin_key,$val,$line);
                        $line = preg_replace($skin_key,$val,$line);
		}
		$skin_tag[] = $line;
	}
	fclose($fp);
	
	return $skin_tag;
  }

}


 
 
?>
