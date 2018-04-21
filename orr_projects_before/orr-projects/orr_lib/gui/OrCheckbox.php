<?php

//
//OrCheckbox.php

//Class สร้าง control checkbox
//@package    Or!Lib
//@author     Suchart Bunhachirat <suchart.orr@gmail.com>
//@copyright  
//@license    
//@version    2554

class OrCheckbox extends OrControls {

    //
    //control จะแสดงเครื่องหมายถูก เมื่อค่า value = check_value
    //@return
    //@access public

    function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);

        $this->property('check_value', 'string');
        /**
         * check_value
         * ค่าต้องการให้แสดงเครื่องหมายถูก ค่าเริ่มต้น = '1'
         */
        $this->OP_[check_value]->set('1');
        $this->OP_[type]->set('checkbox');
    }

    // end of member function OrCheckbox
    //
    //
    //@param mix value ค่าที่กำหนด
    //@return mix
    //@access public

    function get_tag($value = null) {
        if ($value != null) {
            $this->OP_[value]->set($value);
        }

        /**
         * Checking auto_post property
         */
        if ($this->OP_[auto_post]->get()) {
            $this->auto_post();
        }

        $id = $this->get_id_tag();
        $value = $this->OP_[value]->get();

        if ($value == null) {
            $value = $this->OP_[post_value]->get();
        }

        if ($value == null) {
            $value = $this->OP_[default_value]->get();
        }

        if ($value == $this->OP_[check_value]->get()) {
            $check = 'checked="checked"';
        }

        if ($this->OP_[class_name]->get() == null) {
            $class = null;
        } else {
            $class = 'class="' . $this->OP_[class_name]->get() . '"';
        }
        $value = $this->OP_[check_value]->get();
        $type = 'type="' . $this->OP_[type]->get() . '"';
        $value = 'value="' . $value . '"';
        return "<input $id $class $type $value $check>";
    }

}

// end of OrCheckbox
?>
