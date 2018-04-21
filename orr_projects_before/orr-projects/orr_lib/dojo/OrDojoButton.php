<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrDojoButton
 *
 * @author orr
 */
class OrDojoButton extends OrButton{

    function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);
    }

    function get_tag($value = null) {
        if ($value != null) {
            $this->OP_[value]->set($value);
        }

        $id = $this->get_id_tag();

        if ($this->OP_[class_name]->get() == null) {
            $this->OP_[class_name]->set('button');
            $class = 'class="' . $this->OP_[class_name]->get() . '"';
        } else {
            $class = 'class="' . $this->OP_[class_name]->get() . '"';
        }

        $title = 'title="' . $this->OP_[title]->get() . '"';
        $js_event = $this->OP_[js_event]->get();
        $type = 'type="' . $this->OP_[type]->get() . '"';
        $value = 'value="' . $this->OP_[value]->get() . '"';
        $dojo_property = 'dojoType="dijit.form.Button" ';
        if ($this->OP_[image_source]->get() == '') {
            $title = 'title="' . $this->OP_[title]->get() . '"';
            return "<button $dojo_property $id $class $type $value $title $js_event>".$this->OP_[value]->get()."</button>";
        } else {
            $src = 'src="' . $this->OP_[image_source]->get() . '"';
            return "<button $dojo_property $id $type $class $value $title $js_event><img $src></button>";
        }
    }

}
?>
