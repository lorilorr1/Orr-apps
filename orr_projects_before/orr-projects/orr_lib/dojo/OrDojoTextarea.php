<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrDojoTextares
 *
 * @author orr
 */
class OrDojoTextarea extends OrTextarea {

    function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);
    }

    function get_tag($value = null) {
        if ($value != null
            )$this->OP_[value]->set($value);
        if ($this->OP_[auto_post]->get()
            )$this->auto_post();
        $id = $this->get_id_tag();
        $value = $this->OP_[value]->get();
        $rows = 'rows="' . $this->OP_[rows]->get() . '"';
        $cols = 'cols="' . $this->OP_[cols]->get() . '"';

        if ($value == ""
            )$value = $this->OP_[post_value]->get();
        if ($value == ""
            )$value = $this->OP_[default_value]->get();

        if ($this->OP_[class_name]->get() == null) {
            $class = null;
        } else {
            $class = 'class="' . $this->OP_[class_name]->get() . '"';
        }
        $this->clip_value($value);
        $title = 'title="' . $this->OP_[title]->get() . '"';
        $dojo_property = 'dojoType="dijit.Editor" ';
        return $tag = "<textarea $dojo_property $id $class $rows $cols $title>" . $value . "</textarea>\n";
    }

}

?>
