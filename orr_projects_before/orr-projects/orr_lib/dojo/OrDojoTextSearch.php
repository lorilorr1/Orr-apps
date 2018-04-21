<?php

/* * ****************************************************************
 * @version php5
 * @author Suchart Bunhachirat
 * @copyright Copyright &copy; 2007, orr
 * Class ช่องใส่ข้อมูลที่มีปุ่มกดค้นหาข้อมูล เป็นหน้าต่างใหม่
 * ***************************************************************** */

class OrDojoTextSearch extends OrDojoTextbox {

    /**
     * __construct : วิธีการทำงานเริ่มต้นของคลาส
     * @param string
     * @return null
     */
    function __construct($id, $name = null, $idx = null) {
        parent::__construct($id, $name, $idx);
        /*
         * การกำหนดคุณสมบัติ ของคลาส ใช้คำสั่ง
         * $this->property('ชื่อ' , 'ประเภทข้อมูล' ,'ค่าเริ่มต้น');
         */
        // $this->property('ajax_src','string','./dojo-0.4.1-ajax/dojo.js');
        $this->property('popup_url', 'string'); //URL ที่เปิด
        $this->property('popup_id', 'string', $id); //ชื่อหน้าต่าง
        $this->property('popup_width', 'integer', 800); //ความกว้างของหน้าต่าง
        $this->property('popup_hight', 'integer', 600); //ความสูงของหน้าต่าง
        $this->property('content_key_id','string',$id); //ชื่อคีย์สำหรับเรียกขอ้มูล

        /*
         * การกำหนดเหตุการณ์ ของคลาส ใช้คำสั่ง
         * $this->event('ชื่อเหตุการณ์');
         */
        //$this->event('on_load');
    }

    /**
     * __construct : วิธีการทำงานเริ่มต้นของคลาส
     * @param string
     * @return null
     */
    function get_tag($value = null) {
        $url = $this->OP_[popup_url]->get();
        $id = $this->OP_[popup_id]->get();
        $id_content = $id . '_content';
        //$js_onchange = 'onchange="'. "content_refresh(' . $id .','. $url .',' . $id_content .' ); .'"';
        $js_onchange = "content_refresh('$url','$id','$id_content')";
        //$js_onchange = "content_refresh('ajax_content.php','txt_search','txt_search_content')";
        $js_onchange = 'onChange="'. $js_onchange . '"';
        $this->OP_[js_event]->set($js_onchange);
        $this->OP_[class_name]->set('my_content');
        $my_value = parent::get_tag($value);
        $btn_search = new OrDojoButton('btn_search_' . $this->OP_[id]->get());
        $btn_search->OP_[type]->set('button');
        
        $width = $this->OP_[popup_width]->get();
        $hight = $this->OP_[popup_hight]->get();
        //$js_onclick = 'onClick="'."alert(window.document.frm_test.txt_search.value);" .'"';
        $search_value = 'window.document.my_form.' . $this->OP_[id]->get() . '.value';
        //$js_onclick = 'onClick="' . "alert( $search_value );" . '"';
        $js_onclick = 'onClick="' . "win_popup('$url', $search_value , '$id',$width,$hight,'yes');" . '"';
        $btn_search->OP_[js_event]->set($js_onclick);
        $my_value .= ' ' . $btn_search->get_tag('...');
        $my_value .= '<span id="' . $id_content . '" class="my_content"> ไม่มีข้อมูล </span>';
        return $my_value;
    }

}

?>
