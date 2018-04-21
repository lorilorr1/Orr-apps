<?php
//
//OrDb.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//class OrDb
//Class เริ่มของระบบฐานข้อมูล
//property('database','string',$database);
//property('host','string',$host);
//property('user','string',$user);
//property('password','string',$password);
//property('sql','string');
//property('db_error','array');
//property('total_error','integer');

class OrDb extends OrObj {
  // Aggregations: 
  // Compositions: 
  // Attributes: **
  //
  //@access public
  
  public $record;

  //
  //Object ของ connection
  //
  //@access public
  
  public $result;

  //
  //@access public
  
  public $conn;

  //
  //config จาก config_lib.inc
  //
  //@access public
  
  public $config;

  //
  //
  //@param string database Database name
  //@param string host Host name
  //@param string user User name
  //@param OrProperty password    
  //@return 
  //@access public
  
  function OrDb($database, $host = null , $user = null , $password = null )
  {
    global $my_cfg_db;
    if($host == null)$host=$my_cfg_db[host];
		if($user == null)$user=$my_cfg_db[user];
		if($password == null)$password=$my_cfg_db[password];
		$this->property('database','string',$database);
		$this->property('host','string',$host);
		$this->property('user','string',$user);
		$this->property('password','string',$password);
		$this->property('sql','string');
		$this->property('db_error','array');
		$this->property('total_error','integer');
	
  }

  // end of member function OrDb
  //
  //เก็บการเกิด Error Message
  //
  //@param string $value Error Message
  //@return null
  //@access private
  
  function set_error($value)
  {
    $value = array($value);
    $this->OP_[total_error]->set($this->OP_[total_error]->get()+1);
    $this->OP_[db_error]->set(array_merge($this->OP_[db_error]->get(),$value));
    return null;
  }

  // end of member function set_error
  //
  //แสดงรายงาน Error Message
  //
  //@return null
  //@access public
  
  function show_error()
  {
    foreach($this->OP_[db_error]->get() as $key=>$val){
		echo $val."<br>\n";
	}
	$this->reset_error();
	return null;
  }

  // end of member function show_error
  //
  //Retrue True if Error
  //
  //@return boolean
  //@access public
  
  function is_error()
  {
    if($this->OP_[total_error]->get()>0)return true;
  }

  // end of member function is_error
  //
  //
  //@return null
  //@access public
  
  function reset_error()
  {
    $this->OP_[total_error]->set(0);
	return null;
  }

}

 // end of OrDb
?>
