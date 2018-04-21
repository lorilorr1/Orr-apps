<?php

//
//orlabel.php - Copyright 
//@author Suchart Bunhachirat
//@version php4
//************************************************************************
//
//class OrLabel

class OrLabel extends OrControls {

    //
    //
    //@return
    //@access public

    function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);

        $this->property('text', 'string');

        $this->OP_[read_only]->set(true);
        $this->OP_[db_type]->set('text');
        
        /* เริ่ม กำหนดเหตุการณ์ของ Class */
        $this->event('before_text');
        /* จบ กำหนดเหตุการณ์ของ Class */
    }

    // end of member function OrLabel
    //
    //
    //@param mix value ค่าที่กำหนด
    //@return mix
    //@access public

    function get_tag($value = null, $title = null) {

        $value = $this->get_control_value($value);
        $this->clip_value($value);
        debug_mode(__FILE__, __LINE__, $value, 'ค่า value');
        $text = $this->get_control_text($value);
        $title = $this->get_control_title($title);
        $class = $this->get_control_class();
        $id = $this->get_id_tag();
        $field = new OrFieldHidden($this->OP_[id]->get(), $this->OP_[name]->get());
        $span_id = 'id="label_' . $this->OP_[id]->get() . '"';
        return "<SPAN $span_id $title $class>" . $text . "</SPAN>" . $field->get_tag($value) . $this->get_properties_tag();
    }

    function get_control_text($text) {
        debug_mode(__FILE__, __LINE__, $text, 'ค่า Text');
        $EV_[EV_text] = $text;
        $this->OE_before_text($EV_);
        if (!$this->OP_[text]->check_update()) {
            if (!is_null($this->get_format())) {
                $text = $this->get_format();
            } else {
                $text = nl2br($text);
            }
            $this->OP_[text]->set($text);
        }
        debug_mode(__FILE__, __LINE__, $text, 'ค่า Text');
        return $this->OP_[text]->get();
    }

    //Event on class
    function OE_before_text($EV_) {
        /* $EV_text : ค่า TEXT */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[before_text]->get());
        return null;
    }

}

// end of OrLabel
?>
