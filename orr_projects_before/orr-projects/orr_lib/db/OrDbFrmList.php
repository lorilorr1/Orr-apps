<?php

//
//OrDbFrmList.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//Class OrDbFrmList
//สร้างหน้าจอมาตรฐานสำหรับบันทึกแก้ไขข้อมูล

class OrDbFrmList extends OrDbFormList {

    //
    //
    //@access public

    public $control_order_by = null;
    //
    //
    //@access public
    public $control_filter_by = null;
    //
    //Caption fields
    //@access public
    public $caption_fields = array();
    //
    //Object ของ filter_controls ใน form
    //
    //@access public
    public $filter_controls = array();
    //
    //filter fields
    //@access public
    public $filter_value = array();
    //
    //filter use mode
    //@access private
    private $filter_use = array();
    //
    //Caption fields
    //@access public
    public $form_mode = 'list';

    //กำหนดค่า สำหรับการติดต่อฐานข้อมูล
    //@param string id ชื่อ Form
    //@param object db Object ฐานข้อมูล คำสั่ง new OrMysql()
    //@param string table ชื่อ table ที่ใช้
    //@param string key  ชื่อ Field ที่เป็น key บันทึกแก้ไขข้อมูล
    //@return null
    //@access public

    function __construct($id, $db, $skin = null) {
        global $my_cfg;
        parent :: __construct($id);
        $this->property('default_mode', 'string', 'query');
        $this->property('edit_page_url', 'string'); //กำหนด URL ของหน้าแก้ไขข้อมูล
        $this->property('edit_field_link', 'string'); //กำหนด ชื่อ Field ที่ต้องการให้เป็น Link หนาแก้ไขข้อมูล
        $this->property('edit_key_field', 'string'); //กำหนด ชื่อ Field ที่เป็นคีย์แก้ไข
        $this->property('edit_link_target', 'string', '_blank'); //ลักษณะการเปิดหน้าจอแก้ไขเป็น _blank _parent _self _top
        //$this->OrDbFormList($id);
        $this->set_db($db);
        if ($skin == '') {
            $skin = $my_cfg[skins_path] . 'my_list.html';
        }
        $this->set_skin($skin);
        $this->control_order_by = new OrSelectbox('order_by', 'val_msg[order_by]');
        $this->control_order_by->OP_[auto_post]->set(true);
        $this->control_filter_by = new OrTextbox('filter_by', 'val_filter[filter_by]');
        $this->control_filter_by->OP_[auto_post]->set(true);
        $this->control_filter_by->set_size(15, 50);

        $val_ = new OrSysvalue();
        $this->filter_value = $val_->filter;
    }

    //
    //กำหนด filter control object ที่ใช้ใน form
    //
    //@param obj obj ตัวแปร object
    //@return null
    //@access public

    function set_filter_controls($obj, $use = false) {
        if (is_object($obj)) {
            $id = $obj->OP_[id]->get();
            $obj->OP_[name]->set("val_filter[" . $id . "]");
            $obj->OP_[caption]->set($this->controls[$id]->OP_[caption]->get());
            $obj->auto_post(true);
            $this->filter_controls[$id] = $obj;
            $this->filter_use[$id] = $use;
        }
        return null;
    }

    function set_header($header_tag) {
        $this->skin->set_skin_tag('header', $header_tag);
        return null;
    }

    function set_footer($footer_tag) {
        $this->skin->set_skin_tag('footer', $footer_tag);
        return null;
    }

    //tag order by control
    //@return string tag html
    //@access public

    function get_control_order() {
        $this->control_order_by->OP_[option]->set(array_merge(array(
                    '' => ''
                        ), $this->caption_fields));
        return $this->control_order_by->get_tag();
    }

    function get_control_filter() {
        return $this->control_filter_by->get_tag();
    }

    //
    //ตรวจสอบการสร้างคำสั่ง filter กรณีมีการใช้ function และ Group By
    //@param string $field_name ชื่อ field ที่ต้องการตรวจสอบ
    //@return boolean คืนค่าจริง ถ้าเป็น function ที่ใช้คำสั่ง Having

    function is_group_filter($field_name) {
        $field_name = ltrim($field_name);
        $str_function = "/^sum|^count|^max|^min|^avg/";
        if (preg_match($str_function, $field_name)) {
            $my_value = true;
        } else {
            $my_value = false;
        }
        debug_mode(__FILE__, __LINE__, $my_value, 'is_group_filter');
        return $my_value;
    }

    function fetch_record($sql, $filter = null, $filter_msg = '', $reset = false) {

        debug_mode(__FILE__, __LINE__, $sql, 'function fetch_record');


        if (preg_match("/.group by/i", $sql) > 0) {
            $is_group_by = true;
        } else {
            $is_group_by = false;
        }
        debug_mode(__FILE__, __LINE__, $is_group_by, 'is_group_by');
        $val_ = new OrSysvalue();
        $val_msg = $val_->message;
        $db_event = $val_->db_event;
        $val_compare = $val_->compare;
        /* ตรวจสอบการกดปุ่มเพิ่มรายการใหม่ */
        /* if($val_msg[btn_new] == 'New' ){
          header("Location:".$this->OP_[edit_page_url]->get() . '?evt_form_db[navigator]=New');
          } */


        foreach ($this->controls AS $control_id => $control) {
            if ($control->OP_[db_field]->get()) {
                $this->caption_fields[$control->OP_[caption]->get()] = $control_id;
                debug_mode(__FILE__, __LINE__, $control->OP_[caption]->get(), 'caption');
            }
        }

        /**
         * ตรวจสอบ mode การทำงาน ถ้าเป็น Query ไม่ต้องดึงข้อมูล
         * */
        if ($db_event[on_load] == '' AND $this->OP_[default_mode]->get() == 'query' AND $val_msg[btn_filter] != 'Filter' OR $val_msg[btn_query] == 'Query') {
            $this->form_mode = 'query';
        } else {
            /**
             * ตรวจสอบการคัดกรองข้อมูล ตามมาตรฐาน
             * */
            if (is_null($filter)) {
                $filter = new OrSql();
            }
            /**
             * ตรวจสอบคำสั่ง Group By
             */
            if (preg_match("/.group by/i", $sql) > 0) {
                $is_group_by = true;
            } else {
                $is_group_by = false;
            }

            if ($val_msg[btn_filter] == 'Filter') {
                if ($this->filter_value[filter_by] != '') {
                    $filter_msg .= 'ค้นหา ' . $this->filter_value[filter_by] . ' <i>ทุกข้อมูล</i> ';
                    $new_filter = true;
                    //foreach ($this->caption_fields AS $caption => $id) {
                    foreach ($this->controls AS $control_id => $control) {
                         /**
                         * ตรวจสอบค่าประเภทฟิลด์ข้อมูลต้องเป็นข้อความเท่านั้น จึงจะนำมาคัดข้อมูล
                         */
                        if ($control->OP_[db_field]->get() AND $control->OP_[db_type]->get() == 'text') {
                            $id = $control_id;
                            if ($is_group_by) {
                                if ($this->is_group_filter($this->get_filter_name($id))) {
                                    debug_mode(__FILE__, __LINE__, $this->get_filter_name($id), 'filter_name');
                                    $filter->set_cmd_group_filter($this->get_filter_name($id), 'LIKE', $this->filter_value[filter_by], 'OR');
                                    //$filter_msg .= ' ' . $caption . ' ';
                                } else {
                                    debug_mode(__FILE__, __LINE__, $this->get_filter_name($id), 'filter_name');
                                    /**
                                     * ยกเว้นการค้นหาในกรณี Group By
                                     */
                                    //$filter->set_cmd_filter($this->get_filter_name($id), 'LIKE', $this->filter_value[filter_by], 'OR');
                                }
                            } else {
                                $filter->set_cmd_filter($this->get_filter_name($id), 'LIKE', $this->filter_value[filter_by], 'OR', $new_filter);
                                //$filter_msg .= ' ' . $caption . ' ';
                            }
                            $new_filter = false;
                        }
                    }
                }
                /*                 * ค้นจาก Query* */
                if (is_array($this->filter_value)) {
                    $filter_msg .= ' จาก ';
                    $new_filter = true;
                    foreach ($this->filter_value as $id => $value) {
                        if ($id != 'filter_by') {
                            $EV_[EV_field_name] = $id;
                            $EV_[EV_field_value] = $value;
                            $this->OE_set_filter($EV_);

                            if ($value != '') {
                                debug_mode(__FILE__, __LINE__, $this->filter_value[$id], 'Filter_value ' . $id);
                                switch ($val_compare[$id]) {
                                    case 'BETWEEN' :

                                        if ($val_msg[$id . '_II'] != '') {
                                            $value = $value . ' - ' . $val_msg[$id . '_II'];
                                        }
                                        break;

                                    case '':
                                        $val_compare[$id] = '=';

                                    default :
                                }
                                /* if ($val_compare[$id] == '') {
                                  $val_compare[$id] = 'LIKE';
                                  } */

                                if ($is_group_by) {
                                    if ($this->is_group_filter($this->get_filter_name($id))) {
                                        $filter->set_cmd_group_filter($this->get_filter_name($id), $val_compare[$id], $value);
                                        $filter_msg .= ' ' . $this->controls[$id]->OP_[caption]->get() . ' ' . $val_compare[$id] . ' ' . $value;
                                    } else {
                                        $filter->set_cmd_filter($this->get_filter_name($id), $val_compare[$id], $value);
                                        $filter_msg .= ' ' . $this->controls[$id]->OP_[caption]->get() . ' ' . $val_compare[$id] . ' ' . $value;
                                    }
                                } else {
                                    $filter->set_cmd_filter($this->get_filter_name($id), $val_compare[$id], $value, 'AND', $new_filter);
                                    $filter_msg .= ' ' . $this->controls[$id]->OP_[caption]->get() . ' ' . $val_compare[$id] . ' ' . $value;
                                }
                                $new_filter = false;
                            }
                        }
                    }
                }

                if ($val_msg['order_by'] != '') {
                    $sort_field = $val_msg['order_by'];
                    $filter_msg .= ' เรียงตาม ';
                    foreach ($this->caption_fields AS $caption => $id) {
                        if ($val_msg['order_by'] == $id) {
                            $filter->set_cmd_order($id);
                            $filter_msg .= ' ' . $caption . ' ';
                        }
                    }
                }
            } else
            if ($val_msg[btn_filter] == 'No Filter') {
                $reset = true;
            }
            parent :: fetch_record($sql, $filter, $filter_msg, $reset);
        }

        return null;
    }

    //
    //get_form_query : html tag Query form
    //@param array fields
    //@return string html tag

    function get_form_query() {

        $my_table = new OrTable('table_query');
        $my_table->OP_[align_table]->set('center');
        $my_table->OP_[class_name]->set('tbl_query_body');
        $my_table->set_col($this->get_button_filter());
        $my_table->set_col('เงื่อนไข ');
        $my_table->set_col(' ค่าที่ค้นหา ');
        $my_table->set_row();
        foreach ($this->caption_fields AS $caption => $id) {
            if (!is_object($this->filter_controls[$id])) {
                $this->set_filter_controls(new OrTextbox($id));
            }
            $my_compare = new OrSelectbox('val_compare_' . $id . '_', 'val_compare[' . $id . ']');
            $my_compare->OP_[option]->set(array(
                '=' => '=',
                '<>' => '<>',
                '>=' => '>=',
                '<=' => '<=',
                'BETWEEN' => 'BETWEEN',
                'LIKE' => 'LIKE',
                'IN' => 'IN'
            ));
            $my_compare->OP_[default_value]->set($this->get_filter_compare($id)); //สร้าง Function เพื่อค้นหาค่า Default compare
            $my_compare->OP_[auto_post]->set(true);
            //$my_table->set_col($my_control->OP_[caption]->get() , "td_caption");
            debug_mode(__FILE__, __LINE__, $this->filter_controls[$id]->OP_[caption]->get(), 'Filter_controls');
            $my_table->set_col($this->filter_controls[$id]->OP_[caption]->get(), "td_query_caption");
            $my_table->set_col($my_compare->get_tag(), "td_query_compare");
            $my_table->set_col($this->filter_controls[$id]->get_tag(), "td_query_value");
            $my_table->set_row('tr_query_body');
        }

        $my_table->set_col('ค้นคำ เรียงลำดับ');
        $my_table->set_col($this->get_control_filter());
        $my_table->set_col($this->get_control_order());
        $my_table->set_row();

        return $my_table->get_tag();
    }

    //
    //get_form_query : html tag Query form
    //@param array fields
    //@return string html tag

    function get_form_filter() {

        $val_ = new OrSysvalue();
        if (!is_null($var_)) {
            foreach ($val_->filter AS $id => $value) {
                //echo '$id [ ' . $id . ' ] = [ ' . $value . ' ] <br>' ;
                if ($id != 'filter_by') {
                    if (!$this->filter_use[$id]) {
                        $my_filter = new OrFieldHidden($id, 'val_filter[' . $id . ']');
                        $my_filter->OP_[auto_post]->set(true);
                        $my_filter_tag .= $my_filter->get_tag();

                        if ($val_->compare[$id] == 'BETWEEN' OR $val_->message[$id . '_II'] != '') {
                            $my_filter = new OrFieldHidden($id . '_II', 'val_msg[' . $id . '_II]');
                            $my_filter->OP_[auto_post]->set(true);
                            $my_filter_tag .= $my_filter->get_tag();
                        }
                    }
                    $my_compare = new OrFieldHidden('val_compare_' . $id . '_', 'val_compare[' . $id . ']');
                    $my_compare->OP_[auto_post]->set(true);
                    $my_filter_tag .= $my_compare->get_tag();
                }
            }
        }


        /* $my_table = new OrTable('table_query');
          $my_table->OP_[align_table]->set('center');
          $my_table->OP_[class_name]->set('tbl_body');
          $my_table->set_col(' ค้นหา ' . $this->get_control_filter() . ' เรียง ' . $this->get_control_order() . ' ' . $this->get_button_filter() . $my_filter_tag );
          $my_table->set_row(); */
        $my_table = (' ค้นหา ' . $this->get_control_filter() . ' เรียง ' . $this->get_control_order() . ' ' . $this->get_button_filter() . $my_filter_tag );
        return $my_table;
    }

    //
    //get_button_filter : html tag ของปุ่ม filtter
    //@param string $cmd_filter คำสั่ง filter ข้อมูล
    //@return string html tag ของปุ่ม filter

    function get_button_filter($cmd_filter = '') {
        global $my_cfg;
        $btn_filter = new OrButton('btn_filter', 'val_msg[btn_filter]');
        $btn_filter->OP_[class_name]->set("toolbar");
        $btn_filter->OP_[title]->set("ค้นหา");
        $btn_filter->OP_[value]->set("Filter");
        $btn_filter->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/filter.png');
        $my_tag = $btn_filter->get_tag();

        $btn_query = new OrButton('btn_query', 'val_msg[btn_query]');
        $btn_query->OP_[class_name]->set("toolbar");
        $btn_query->OP_[title]->set("ตั้งคำถาม");
        $btn_query->OP_[value]->set("Query");
        $btn_query->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/query.png');
        $my_tag .= $btn_query->get_tag();

        if ($cmd_filter != '' OR $this->OP_[cmd_filter]->get() != '' OR $this->OP_[cmd_group_filter]->get() != '') {
            $btn_filter->OP_[title]->set("ยกเลิก ค้นหา");
            $btn_filter->OP_[value]->set("No Filter");
            $btn_filter->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/no_filter.png');
            $my_tag .= $btn_filter->get_tag();
        }

        if ($this->OP_[edit_page_url]->get() != '') {
            $btn_new = new OrButton('btn_new', 'val_msg[btn_new]');
            $btn_new->OP_[class_name]->set("toolbar");
            $btn_new->OP_[title]->set("เพิ่มรายการใหม่");
            $btn_new->OP_[value]->set("New");
            $btn_new->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/new_document.png');
            $btn_new->OP_[type]->set('button');
            $event = "onClick";
            $js_script = "window.location.href = '" . $this->OP_[edit_page_url]->get() . "?evt_form_db[navigator]=New'";
            $btn_new->OP_[js_event]->set("$event = \"" . $js_script . "\"");
            $my_tag .= $btn_new->get_tag();
        }

        return $my_tag;
    }

    //สร้าง และคืนค่า tag
    //@return array
    //@access public

    function get_tag($navigator_skin_file = '') {
        global $my_cfg;
        if ($navigator_skin_file == '') {
            $navigator_skin_file = $my_cfg[skins_path] . 'list_button.html';
        }
        if ($this->form_mode == 'query') {
            $this->set_header('<b>การตั้งคำถาม เพื่อค้นหาข้อมูล อย่างละเอียด กรุณาใส่ข้อมูลในช่องด้านล่าง เพื่อกำหนดเงื่อนไข<b>');
            $this->set_footer(null);
            $this->skin->set_skin_tag('body', $this->get_form_query());
        } else {
            $this->skin->set_skin_tag('query', $this->get_form_filter());
            $this->skin->set_skin_tag('body', $this->get_list_tag());
            $this->skin->set_skin_tag('navigator', $this->get_navigator_tag($navigator_skin_file));
        }
        //$this->skin->set_skin_tag('status' , $this->get_status_tag());
        $this->set_body($this->skin->get_tag());
        return parent :: get_tag();
    }

    function OE_set_filter($EV_) {
        /* เหตุการณ์ กำหนดค่าสั่ง Filter สุชาติ บุญหชัยรัตน์ 28/1/2550 */
        /* $EV_field_name = ชื่อ Field */
        /* $EV_field_value = ค่าที่ Filter */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[on_load]->get());
        return null;
    }

    function OE_current_record($EV_) {
        /* $EV_record : array ค่าเหตุการณ์ที่เกิดขึ้น */
        extract($EV_, EXTR_OVERWRITE);

        $field_link = $this->OP_[edit_field_link]->get();

        if ($field_link != '') {
            $page_link = $this->OP_[edit_page_url]->get();
            $key_link = $this->OP_[edit_key_field]->get();
            $link_target = $this->OP_[edit_link_target]->get();
            $event_link = '<a href="' . $page_link . '?val_filter[' . $key_link . ']=' . $EV_record[$key_link] . '&val_compare[' . $key_link . ']==&val_msg[btn_filter]=Filter" target="' . $link_target . '" >' . $EV_record[$field_link] . '</a>';
            $this->controls[$field_link]->OP_[text]->set($event_link);
        }
        eval($this->OE_[current_record]->get());
        return null;
    }

}

?>
