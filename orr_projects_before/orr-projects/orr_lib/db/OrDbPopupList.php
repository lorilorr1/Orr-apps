<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrDbPopupList
 * หน้าจอรายงานสำหรับเป็นป๊อปอัพ เลือกรายการใช้งานคู่กับ OrDojoTextSearch
 * @author     Suchart Bunhachirat <suchartbu@gmail.com>
 * @copyright 
 * @license
 * @version
 */
class OrDbPopupList extends OrDbFrmList{
    function  __construct($id, $db, $skin = null) {
        parent::__construct($id, $db, $skin);
        $val_ = new OrSysvalue();
        $this->filter_value = $val_->filter;
        $this->property('form_return','string','my_form'); //กำหนด ชื่อฟอร์ม ที่คืนค่ากลับในหน้าเดิม
        $this->property('control_return','string',$val_->message[control_id]); //กำหนด ชื่อ Control ที่คืนค่ากลับในหน้าเดิม
        //กำหนดแสดงหน้าจอแสดงข้อมูลเป็นค่าเริ่มต้น
        $this->OP_[default_mode]->set('list');
        $msg_control_id = new OrFieldHidden('val_msg[control_id]');
        $this->set_body($msg_control_id->get_tag($val_->message[control_id]));
    }

    //เพิ่มปุ่มเพิ่มคืนค่าที่เลือกรายการ
  //@return array
  //@access public

     function OE_current_record($EV_)
  {
		/*$EV_record : array ค่าเหตุการณ์ที่เกิดขึ้น*/
		extract($EV_, EXTR_OVERWRITE);

                $field_link = $this->OP_[edit_field_link]->get();

                if($field_link != ''){
                        $page_link = $this->OP_[edit_page_url]->get();
                        $key_link = $this->OP_[edit_key_field]->get();
                        //$event_link = '<a href="' . $page_link . '?val_filter[' . $key_link .']=' . $EV_record[$key_link] . '&val_compare[' . $key_link . ']==&val_msg[btn_filter]=Filter" target="_parent" >' . $EV_record[$field_link] . '</a>';
                        //$return_link = '<div>-></div>';
                        //$this->controls[$field_link ]->OP_[text]->set($return_link . $event_link);
                        $return_link = 'window.opener.document.' . $this->OP_[form_return]->get() .'.' . $this->OP_[control_return]->get() . ".value='" . $EV_record[$field_link] . "';";
                        $return_link .= 'window.opener.document.' . $this->OP_[form_return]->get() .'.' . $this->OP_[control_return]->get() . ".focus();";
                        $return_link .= 'window.close();';
                        //$return_link =  "return_to_opener('" . $EV_record[$field_link] . "','". $this->OP_[form_return]->get() .  "','". $this->OP_[control_return]->get() ."');" ;
                        $return_link ='<a href="JavaScript:' . $return_link . '">'.$EV_record[$field_link].'</a>';
                        $this->controls[$field_link ]->OP_[text]->set($return_link);
                }
		eval ($this->OE_[current_record]->get());
		return null;
  }
}
?>
