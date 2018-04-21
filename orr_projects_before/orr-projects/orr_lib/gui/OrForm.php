<?php

/**
//Created on 12-Mar-06
//orgui.php - Copyright 
//@author Suchart Bunhachirat
//@version php5
//*************************************************************************
//
//class OrForm
//สร้าง Form สำหรับ Controls
//Properties
//
//property('id','string');
//property('idx','string');
//property('name','string');
//property('class_name','string');
//property('method','string','post');
//property('action','string',$PHP_SELF);
*/

class OrForm extends OrGui {

    //
    //Object ของ controls ใน form
    //
  //@access public

    public $controls = array();
    //
    //ค่าที่ส่งกลับของ controls ใน form
    //
  //@access public

    public $val_controls = array();
    //
    //Object OrSkin ที่ใช้งาน
    //
  //@access public

    public $skin;

    function __construct($id, $name = "") {
        global $PHP_SELF;
        $val_ = new OrSysvalue();
        $this->val_controls = $val_->controls;
        parent:: __construct($id, $name);
        //$this->property('id','string',$id);
        //$this->property('idx','string');
        //$this->property('name','string',$name);
        $this->property('class_name', 'string');
        $this->property('tag', 'string');
        $this->property('body', 'array');
        $this->property('method', 'string', 'post');
        $this->property('action', 'string', $PHP_SELF);
        $this->property('db_form', 'boolean', false);
    }

    //
    //กำหนด skin ที่ใช้งาน
    //
  //@param string skin_src path skin file
    //@return null
    //@access public

    function set_skin($skin_src) {
        $this->skin = new OrSkin($skin_src); //ยังไม่พบการใช้งาน รอตรวจสอบ
        return null;
    }

    //
    //เพิ่ม html tag ใน form
    //
  //@param mix value ค่า string หรือ array ของ html tag
    //@return null
    //@access public

    function set_body($value) {
        if (!is_array($value)) {
            $value = array($value);
        }
        $this->OP_[body]->set(array_merge($this->OP_[body]->get(), $value));
        return null;
    }

    //
    //กำหนด control object ที่ใช้ใน form
    // TODO : แก้ไขให้รองรับ
    //@param obj obj ตัวแปร object กุลุม OrControls
    //@param string caption ข้อความอธิบาย
    //@param boolean is_db_field เป็น ture กรณีเป็น field ฐานข้อมูล
    //@return null
    //@access public

    function set_controls($obj, $caption = '', $is_db_field = true) {
        if (is_object($obj)) {
            //if($caption != '')$obj->OP_[caption]->set($caption);
            $c_id = ($obj->OP_[idx]->get() != "") ? ("[" . $obj->OP_[id]->get() . "][" . $obj->OP_[idx]->get() . "]") : ("[" . $obj->OP_[id]->get() . "]");
            //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | c_id =".$c_id."<br>";*/
            if ($this->is_OP($obj, "db_field")) {
                if ($this->OP_[db_form]->get() AND $is_db_field)
                    $obj->OP_[db_field]->set(true);
                if ($obj->OP_[db_field]->get()) {
                    $obj->OP_[name]->set("val_controls[db_field]$c_id");
                } else {
                    $obj->OP_[name]->set("val_controls$c_id");
                }
            } else {
                $obj->OP_[name]->set("val_controls$c_id");
            }
            if ($obj->OP_[idx]->get() != "") {
                $this->controls[$obj->OP_[id]->get()][$obj->OP_[idx]->get()] = $obj;
            } else {
                $this->controls[$obj->OP_[id]->get()] = $obj;
            }
        } else {
            die(__FILE__ . " | " . __LINE__ . " |set_controls | [ $obj ]This isn't object");
        }
        /** กำหนดคุณสมบัติของ control ใน form
         * caption
         * title
         * class_name
         * */
        $id = $obj->OP_[id]->get();
        if ($caption == '') {
            $datafield = $this->get_datafield($id);
            $this->controls[$id]->OP_[caption]->set($datafield[name]);
            $this->controls[$id]->OP_[title]->set($datafield[description]);
        } else {
            $this->controls[$id]->OP_[caption]->set($caption);
        }

        return null;
    }

    //
    //form tag
    //
  //@return array ค่า html tag
    //@access public

    function get_tag() {
        $id = 'id="' . $this->OP_[id]->get() . '"';
        $name = 'name="' . $this->OP_[name]->get() . '"';
        $action = 'action="' . $this->OP_[action]->get() . '"';
        $method = 'method="' . $this->OP_[method]->get() . '"';
        $body = $this->OP_[body]->get();
        $tag = array("<form $action $id $method $name>\n");
        foreach ($body as $val) {
            $tag[] = $val . "\n";
        }
        $tag[] = "</form>";
        return $tag;
    }

    //คืนค่า คำอธิบายของ Field
    //@param string field_id
    //@return array
    //@access public

    function get_datafield($field_id) {
        global $my_cfg_sec;
        $db_sec = new OrMysql($my_cfg_sec[db]);
        $sql = "SELECT * FROM `my_datafield`WHERE field_id='" . $field_id . "';";
        $db_sec->get_query($sql);
        $db_sec->get_record();
        if ($db_sec->record[name] != '') {
            $my_value[name] = $db_sec->record[name];
            $my_value[description] = $db_sec->record[description];
        } else {
            $my_value[name] = $field_id;
            $my_value[description] = 'ไม่ได้กำหนด ชื่อเรียก';
        }

        return $my_value;
    }

}

?>
