<?php

//
//Created on 11-Mar-06
//
//To change the template for this generated file go to
//Window - Preferences - PHPeclipse - PHP - Code Templates
//
//
//OrMysql.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - php5
//*************************************************************************
//
//class OrMysql
//Class Connect to MySQL database
//property('database','string',$database);
//property('host','string',$host);
//property('user','string',$user);
//property('password','string',$password);
//property('sql','string');
//property('db_error','array');
//property('total_error','integer');

class OrMysql extends OrDb {

    //
    //กำหนดค่าเริ่มต้นการเชื่อมต่อฐานข้อมูล
    //ตัวอย่างการเรียกใช้ฐานข้อมูล my_sec มีคำสั่งดังนี้ 
    //$my_db = new OrMysql('my_sec');
    //@param string database Database name
    //@param string host Host name
    //@param string user User name
    //@param string password    
    //@return 
    //@access public

    function OrMysql($database, $host = null, $user = null, $password = null) {
        $this->OrDb($database, $host, $user, $password);
    }

    // end of member function OrMysql
    //
  //Connecting Database
    //
  //@param string database Database name
    //@param boolean pconnect
    //@return connection Database connect
    //@access public

    function get_host_conn($database = null, $pconnect = false) {
        global $my_cfg_db;
        if (!is_null($database))
            $this->OP_[database]->set($database);
        if ($pconnect) {
            $conn = @mysql_pconnect($this->OP_[host]->get(), $this->OP_[user]->get(), $this->OP_[password]->get()) or
                    die(__FILE__ . " | " . __LINE__ . " |get_host_conn | " . mysql_error());
        } else {
            $conn = @mysql_connect($this->OP_[host]->get(), $this->OP_[user]->get(), $this->OP_[password]->get()) or
                    die(__FILE__ . " | " . __LINE__ . " |get_host_conn | " . mysql_error());
        }
        if (!mysql_select_db($this->OP_[database]->get(), $conn))
            die("ö¡ Database" . mysql_error());

        $charset = "SET NAMES '" . $my_cfg_db[charset] . "'";
        if ($my_cfg_db[charset] != '')
            mysql_query($charset, $conn) or die(__FILE__ . " | " . __LINE__ . " |get_host_conn | " . mysql_error());

        return $this->conn = $conn;
    }

    // end of member function get_host_conn
    //
  //Query Database
    //สอบถามข้อมูลโดยคำสั่ง SQL
    //
  //@param string sql SQL string
    //@return result Query result
    //@access public

    function get_query($sql) {
        $this->OP_[sql]->set($sql);
        debug_mode(__FILE__, __LINE__, $sql, 'function get_query');
        $result = @mysql_query($this->OP_[sql]->get(), $this->get_host_conn());
        if (!$result)
            $this->set_error('sql=' . $sql . ' [' . mysql_error() . ']<br>');
        return $this->result = $result;
    }

    // end of member function get_query
    //
  //get record array
    //
  //@return array record result
    //@access public

    function get_record() {

        return $this->record = @mysql_fetch_array($this->result);
    }

    // end of member function get_record
    //
  //get record array
    //
  //@return array record result
    //@access public

    function get_total_row() {
        return @mysql_num_rows($this->result);
    }

    // end of member function get_total_row
    //
  //get field name from index
    //
  //@param int field_index number to get field name
    //@return string field name
    //@access public

    function get_field_name($field_index = 0) {
        return @mysql_field_name($this->result, $field_index);
    }

    // end of member function get_field_name
    //
  //get id number from add record
    //
  //@return int last add id
    //@access public

    function get_insert_id() {
        return @mysql_insert_id($this->conn);
    }

    // end of member function get_insert_id
    //
  //close database connection
    //
  //@return boolean
    //@access public

    function close_conn() {
        return @mysql_close($this->conn);
    }

}

?>
