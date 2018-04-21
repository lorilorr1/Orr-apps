<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrContent
 * ส่งค่ากลับ
 * @author orr
 */
class OrContent extends OrObj {

    private $body = array();

    //put your code here
    function __construct($text='') {
        $this->property('text', 'string', $text);
    }

    /**
     * set_body : รับค่า tag ที่เป็น array จาก class อื่นๆ เช่น OrSkin
     * @param $tag ค่าtagที่เป็นarray
     * @return null
     */
    function set_body($tag) {
        if (!is_array($tag)) {
            $tag = array($tag);
        }
        $this->body = array_merge($this->body, $tag);
        return null;
    }

    /**
     * get_body : คืนค่าจาก array เป็น text
     * @param null
     * @return null
     */
    function get_body() {
        foreach ($this->body as $line) {
            $my_value .= $line;
        }
        return $my_value;
    }

    public function show() {
        header('Content-type: text/plain');
        if($this->OP_[text]->get() != ''){
            print($this->OP_[text]->get());
        }
        print($this->get_body());
    }

}

?>
