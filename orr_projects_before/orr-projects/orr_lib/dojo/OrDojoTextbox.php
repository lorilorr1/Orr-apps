<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrDojoTextbox
 * ตรวจสอบรูปแบบการคีย์ข้อมูลตาม regExp
 * @author orr
 */
class OrDojoTextbox extends OrTextbox {

    function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);
        $this->property('reg_exp', 'string'); //รูปแบบข้อมูลตาม regExp เช่น '[^\s]+'
        $this->property('invalid_message', 'string', 'Invalid Text'); //ข้อความที่ต้องการแสดงเมื่อรูปแบบข้อมูลไม่ถูกต้อง
        //$this->property('required', 'boolean', TRUE); //เป็นช่องที่ต้องการข้อมูลใส่ TRUE ไม่ต้องการใส่ FALSE ##แทนด้วย checknull
    }

    function get_tag($value = null) {
        if ($value != null) {
            $this->OP_[value]->set($value);
        } else if (is_numeric($value)) {
            $this->OP_[value]->set($value);
        }

        if ($this->OP_[auto_post]->get()) {
            $this->auto_post();
        }

        $id = $this->get_id_tag();
        $value = $this->OP_[value]->get();
        $maxlength = $this->OP_[maxlength]->get();
        $size = $this->OP_[size]->get();
        $type = 'type="' . $this->OP_[type]->get() . '"';

        if ($this->OP_[password]->get()

            )$type = 'type="password"';
        if ($value == null AND !is_numeric($value)) {
            $value = $this->OP_[post_value]->get();
        }

        if ($value == null AND !is_numeric($value)) {
            $value = $this->OP_[default_value]->get();
        }

        if ($maxlength == 0) {
            $maxlength = null;
        } else {
            $maxlength = 'maxlength="' . $maxlength . '"';
        }

        if ($size == 0) {
            $size = null;
        } else {
            //$size = 'size="'.$size.'"';
            $size = $size * 7;
            $size = 'style="width: ' . $size . 'px;"';
        }

        if ($this->OP_[class_name]->get() == null) {
            $class = null;
        } else {
            $class = 'class="' . $this->OP_[class_name]->get() . '"';
        }


        if ($this->OP_[check_null]->get()){
            $dojo_required = ' required="true" ';
        }  else {
            $dojo_required = ' required="false" ';
        }
        if ($this->OP_[reg_exp]->get() == null) {
            $dojo_reg_exp = null;
        } else {
            $dojo_reg_exp = ' regExp="' . $this->OP_[reg_exp]->get() . '" ';
        }

        $dojo_invalid_message = ' invalidMessage="' .  $this->OP_[invalid_message]->get() . '" ';
        $this->clip_value($value);
        $value = 'value="' . $value . '"';
        $title = 'title="' . $this->OP_[title]->get() . '"';
        $js_event = $this->OP_[js_event]->get();
       // $dojo_property = 'dojoType="dijit.form.ValidationTextBox" regExp="[^\s]+" required="true" invalidMessage="Invalid Non-Space Text."';
        $dojo_property = 'dojoType="dijit.form.ValidationTextBox" ' . $dojo_reg_exp . $dojo_required .  $dojo_invalid_message ;
        $my_value = "<input $id $class  $type $dojo_property $maxlength $size $value $title $js_event>" . $this->get_properties_tag();
        return $my_value;
    }

}
?>
