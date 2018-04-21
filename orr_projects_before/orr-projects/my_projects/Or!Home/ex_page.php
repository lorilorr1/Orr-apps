<?php

/*
 * ตัวอย่างการสร้างหน้าจอ HTML
 */
require_once('../../orr_lib/Or.php');
require_once('config.inc.php');

class my_page extends OrHtml {

    function __construct($title = '') {
        global $my_cfg;
        parent::__construct($title);
        //$this->set_skin($my_cfg[skins_path] .'default.html');
        $frm_test = new OrForm('my_form');
        $txt_search = new OrDojoTextSearch('txt_search');    
        $txt_search->OP_[popup_url]->set('ex_popup_list.php');
        $txt_search2 = new OrDojoTextSearch('txt_search2');
        $txt_search2->OP_[popup_url]->set('ex_popup_list.php');
        $js_onclick = 'onClick="'."alert( 'กดแล้ว' );" .'"';
        $lbl_test1 = new OrLabel('lbl_test1');
        $lbl_test1->OP_[js_event]->set($js_onclick);
        $lbl_test1->OP_[text]->set('ทดสอบ label');
        //$txt_search->OP_[popup_id]->set('win_popup');
        $this->set_body("ทดสอบการใช้ Controls");
        $frm_test->set_body('<br>' . $txt_search->get_tag());
        $frm_test->set_body('<br>' . $txt_search2->get_tag());
        $frm_test->set_body('<br>' . $lbl_test1->get_tag());
        $this->set_body($frm_test->get_tag());
       
        $this->show();
    }

}

$my = new my_page('ตัวอย่างการสร้างหน้าจอ HTML');
?>
