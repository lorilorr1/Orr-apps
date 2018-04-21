<?php

//
//OrDbFrmForm.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//Class OrDbFrmForm
//สร้างหน้าจอมาตรฐานสำหรับบันทึกแก้ไขข้อมูล

class OrDbFrmForm extends OrDbForm {

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
    //field name ที่ต้องการเปลียนชื่อ
    //@access private
    private $filter_name = array();
    //
    //Caption fields
    //@access public
    public $form_mode = 'form';

    //กำหนดค่า สำหรับการติดต่อฐานข้อมูล
    //@param string $id ชื่อ Form
    //@param object $db Object ฐานข้อมูล คำสั่ง new OrMysql()
    //@param string $table ชื่อ table ที่ใช้
    //@param string $key  ชื่อ Field ที่เป็น key บันทึกแก้ไขข้อมูล
    //@param string $skin path ที่อยู่ของไฟล์ Skin
    //@return null
    //@access public

    function __construct($id, $db, $table, $key, $skin = null) {
        global $my_cfg;
        parent :: __construct($id);
        $this->property('list_page_url', 'string'); // URL หน้ารายการข้อมูล
        $this->set_db($db);
        $this->set_db_table($table);
        $this->set_db_key($key);
        if ($skin == '') {
            $skin = $my_cfg[skins_path] . 'my_form.html';
        }
        $this->set_skin($skin);
        $this->control_order_by = new OrSelectbox('order_by', 'val_msg[order_by]');
        $this->control_order_by->OP_[auto_post]->set(true);
        $this->control_filter_by = new OrTextbox('filter_by', 'val_filter[filter_by]');
        $this->control_filter_by->OP_[auto_post]->set(true);
        $this->control_filter_by->set_size(20);

        $val_ = new OrSysvalue();
        $this->filter_value = $val_->filter;
    }

    //
    //กำหนด control object ที่ใช้ใน form
    //
    //@param obj obj ตัวแปร object
    //@return null
    //@access public

    function set_filter_controls($obj) {
        if (is_object($obj)) {
            $id = $obj->OP_[id]->get();
            //$obj->auto_post(true);
            $obj->OP_[name]->set("val_filter[" . $id . "]");
            $obj->OP_[caption]->set($this->controls[$id]->OP_[caption]->get());
            $this->filter_controls[$id] = $obj;
        }
        return null;
    }

    //
    //กำหนด control object ที่ใช้ใน form
    //
    //@param obj obj ตัวแปร object
    //@return null
    //@access public

    function set_filter_name($id, $name) {
        $this->filter_name[$id] = $name;
        return null;
    }

    function set_header($header_tag) {
        $this->skin->set_skin_tag('header', $header_tag);
        return null;
    }

    //ค่า Field name ที่ต้องการ
    //@return string
    //@access public

    function get_filter_name($id) {
        if (!is_null($this->filter_name[$id])) {
            $id = $this->filter_name[$id];
        }
        return $id;
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

    function fetch_record($sql, $filter = null, $filter_msg = '', $reset = false) {

        if (is_null($filter)) {
            $filter = new OrSql();
        }
        $val_ = new OrSysvalue();
        $val_msg = $val_->message;
        //$val_filter = $val_->filter;
        $val_compare = $val_->compare;
        /* ตรวจสอบการกดปุ่มแสดงรายการ */
        /* if($val_msg[btn_list] == 'List' ){
          header("Location:".$this->OP_[list_page_url]->get() );
          } */

        foreach ($this->controls AS $control_id => $control) {
            if ($control->OP_[db_field]->get()) {
                $this->caption_fields[$control->OP_[caption]->get()] = $control_id;
                debug_mode(__FILE__, __LINE__, $control->OP_[caption]->get(), 'caption');
            }
        }

        if ($val_msg[btn_query] == 'Query') {
            $this->form_mode = 'query';
        } else {
            /**
             * ตรวจสอบการคัดกรองข้อมูล ตามมาตรฐาน
             * */
            if ($val_msg[btn_filter] == 'Filter') {
                if ($this->filter_value[filter_by] != '') {
                    $filter_msg .= 'ค้นหา ' . $this->filter_value[filter_by] . ' ใน ';
                    //foreach ($this->caption_fields AS $caption => $id) {
                    foreach ($this->controls AS $control_id => $control) {
                        //$filter->set_cmd_filter($id, 'LIKE', $this->filter_value[filter_by], 'OR');
                        /**
                         * ตรวจสอบค่าประเภทฟิลด์ข้อมูลต้องเป็นข้อความเท่านั้น จึงจะนำมาคัดข้อมูล
                         */
                        if ($control->OP_[db_field]->get() AND $control->OP_[db_type]->get() == 'text') {
                            $filter->set_cmd_filter($control_id, 'LIKE', $this->filter_value[filter_by], 'OR');
                            $filter_msg .= ' ' . $control->OP_[caption]->get() . ' ';
                        }
                    }
                } else {
                    /*                     * ค้นจาก Query* */
                    if (is_array($this->filter_value)) {
                        $filter_msg .= ' ค้นจาก ';
                        foreach ($this->filter_value as $id => $value) {

                            /* $EV_[EV_field_name] = $id;
                              $EV_[EV_field_value] = $value;
                              $this->OE_set_filter($EV_); */

                            if ($value != '') {
                                if ($val_compare[$id] == '') {
                                    $val_compare[$id] = '=';
                                }
                                $filter->set_cmd_filter($id, $val_compare[$id], $value);
                                $filter_msg .= ' ' . $this->controls[$id]->OP_[caption]->get() . ' ' . $val_compare[$id] . ' ' . $value;
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
        $my_table->OP_[class_name]->set('tbl_body');
        $my_table->set_col($this->get_button_filter());
        $my_table->set_col('เงื่อนไข ');
        $my_table->set_col(' ค่าที่ค้นหา ');
        $my_table->set_row();
        foreach ($this->caption_fields AS $caption => $id) {
            if (!is_object($this->filter_controls[$id])) {
                $this->set_filter_controls(new OrTextbox($id));
            }
            $my_compare = new OrSelectbox('val_compare[' . $id . ']');
            $my_compare->OP_[option]->set(array(
                '=' => '=',
                '<>' => '<>',
                '>=' => '>=',
                '<=' => '<=',
                'BETWEEN' => 'BETWEEN',
                'LIKE' => 'LIKE',
                'IN' => 'IN'
            ));
            $my_compare->OP_[default_value]->set('LIKE');
            //$my_table->set_col($my_control->OP_[caption]->get() , "td_caption");
            debug_mode(__FILE__, __LINE__, $this->filter_controls[$id]->OP_[caption]->get(), 'Filter_controls');
            $my_table->set_col($this->filter_controls[$id]->OP_[caption]->get(), "td_caption");
            $my_table->set_col($my_compare->get_tag(), "td_data");
            $my_table->set_col($this->filter_controls[$id]->get_tag(), "td_data");
            $my_table->set_row('tr_body');
        }

        $my_table->set_col('เรียงลำดับ');
        $my_table->set_col($this->get_control_order());
        $my_table->set_col('');
        $my_table->set_row();

        return $my_table->get_tag();
    }

    //
    //get_button_filter : html tag ของปุ่ม filtter
    //@param string $cmd_filter คำสั่ง filter ข้อมูล
    //@return string html tag ของปุ่ม filter

    function get_button_filter($cmd_filter = '') {
        global $my_cfg;
        $btn_filter = new OrDojoButton('btn_filter', 'val_msg[btn_filter]');
        $btn_filter->OP_[class_name]->set("toolbar");
        $btn_filter->OP_[title]->set("ค้นหา");
        $btn_filter->OP_[value]->set("Filter");
        $btn_filter->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/filter.png');
        $my_tag = $btn_filter->get_tag();

        $btn_query = new OrDojoButton('btn_query', 'val_msg[btn_query]');
        $btn_query->OP_[class_name]->set("toolbar");
        $btn_query->OP_[title]->set("ตั้งคำถาม");
        $btn_query->OP_[value]->set("Query");
        $btn_query->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/query.png');
        $my_tag .= $btn_query->get_tag();

        if ($cmd_filter != '' OR $this->OP_[cmd_filter]->get() != '') {
            $btn_filter_reset = new OrDojoButton('btn_filter_reset', 'val_msg[btn_filter]');
            $btn_filter_reset->OP_[title]->set("ยกเลิก ค้นหา");
            $btn_filter_reset->OP_[value]->set("No Filter");
            $btn_filter_reset->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/no_filter.png');
            $my_tag .= $btn_filter_reset->get_tag();
        }

        if ($this->OP_[list_page_url]->get() != '') {
            $btn_list = new OrDojoButton('btn_list', 'val_msg[btn_list]');
            $btn_list->OP_[class_name]->set("toolbar");
            $btn_list->OP_[title]->set("รายการข้อมูล");
            $btn_list->OP_[value]->set("List");
            $btn_list->OP_[image_source]->set($my_cfg[skins_path] . 'image/button/list_page.png');
            $btn_list->OP_[type]->set('button');
            $event = "onClick";
            $js_script = "window.location.href = '" . $this->OP_[list_page_url]->get() . "'";
            $btn_list->OP_[js_event]->set("$event = \"" . $js_script . "\"");
            $my_tag .= $btn_list->get_tag();
        }

        return $my_tag;
    }

    //สร้าง และคืนค่า tag
    //@return array
    //@access public

    function get_tag($navigator_skin_file = '') {
        global $my_cfg;

        if ($navigator_skin_file == '') {
            $navigator_skin_file = $my_cfg[skins_path] . 'form_button.html';
        }
        if ($this->form_mode == 'query') {
            $this->set_header(null);
            //$this->set_footer(null);
            $this->skin->set_skin_tag('body', $this->get_form_query());
        } else {
            $this->skin->set_skin_tag('message', $this->OP_[message]->get());
            //$this->skin->set_skin_tag('filter',$this->get_form_query());
            $this->skin->set_skin_tag('body', $this->get_body_tag());
            $this->skin->set_skin_tag('navigator', $this->get_navigator_tag($navigator_skin_file));
        }
        $this->skin->set_skin_tag('status', $this->get_status_tag());
        $this->set_body($this->skin->get_tag());
        return parent :: get_tag();
    }

    //เหตุการณ์หลังเกิดความผิดพลาด
    //  @param string $cmd_filter คำสั่ง filter ข้อมูล
    //  @return null

    function OE_after_error($EV_) {
        /* เหตุการณ์หลังจากเกิดความผิดพลาด สุชาติ บุญหชัยรัตน์ 27 ธันวาคม 2546 */
        /* $EV_control_id = ชื่อ control */
        /* $EV_control_val = ค่า control */
        /* $EV_error_type = ประเภทความผิดพลาด */
        extract($EV_, EXTR_OVERWRITE);
        $this->OP_[on_error]->set(true);
        $this->OP_[message]->set('เกิดความผิดพลาด');
        eval($this->OE_[after_error]->get());
        $caption = '<b>' . $this->controls[$EV_control_id]->OP_[caption]->get() . '  : กรุณาใส่ข้อมูล-> </b>';
        $this->controls[$EV_control_id]->OP_[caption]->set($caption);
        return null;
    }

}

?>
