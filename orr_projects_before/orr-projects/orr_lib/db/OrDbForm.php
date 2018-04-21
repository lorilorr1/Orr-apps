<?php

//
//OrDbForm.php - Copyright 
//@author Suchart Bunhachirat
//@version php4 - 5
//************************************************************************
//
//class OrDbForm
//

class OrDbForm extends OrDojoForm {

    //
    //Object ฐานข้อมูล
    //@access public

    public $db = null;
    //
    //ชื่อ Table ที่ต้องการเพิ่ม แก้ไข ลบ ข้อมูล
    //@access public

    public $db_table = "";
    //
    //ชื่อ kay field ของ Table ที่ต้องการเพิ่ม แก้ไข ลบ ข้อมูล
    //@access public

    public $db_key = array();

    function __construct($id, $name = "") {
        /* Constructors */
        parent :: __construct($id, $name);
        $this->OP_[db_form]->set(true);
        /* เริ่ม กำหนดคุณสมบัติของ Calss */
        $this->property('total_rec', 'integer', 1);
        $this->property('cur_rec', 'integer', 1);
        $this->property('cmd_filter', 'string');
        $this->property('cmd_order', 'string');
        $this->property('cmd_msg', 'string');
        $this->property('sec_status_tag', 'string');
        $this->property('record_status', 'string');
        $this->property('chg_owner', 'boolean', false);
        $this->property('on_load', 'integer', 0);
        $this->property('on_error', 'boolean', false);
        $this->property('size', 'integer');
        $this->property('what_error', 'string'); //ใช้ส่งค่ากลับเพื่อแจ้งเหตุการณ์ after_error
        $this->property('message', 'string'); //ข้อมูลแจ้งกลับผู้ใช้งาน
        $this->property('column', 'integer', 1); //จำนวนคอลัมน์ในฟอร์ม

        /* จบ กำหนดคุณสมบัติของ Calss */

        /* เริ่ม กำหนดเหตุการณ์ของ Calss */
        $this->event('on_load');
        $this->event('new_record');
        $this->event('before_add');
        $this->event('after_add');
        $this->event('before_save');
        $this->event('after_save');
        $this->event('before_delete');
        $this->event('after_delete');
        $this->event('after_error');
        /* จบ กำหนดเหตุการณ์ของ Calss */
    }

    //ตรวจสอบ กำหนด Object ฐานข้อมูล 
    //@param object obj Object ฐานข้อมูล
    //@return null
    //@access public

    function set_db($obj) {
        if (is_object($obj)) {
            $this->db = $obj;
        } else {
            die("form->set_db : [$obj] This isn't object");
        }
        return null;
    }

    //ดึงข้อมูลจากฐานข้อมูล 
    //@param string sql คำสั่ง SQL
    //@param string cmd_filter คำสั่ง Filter Record ที่ต้องการ
    //@param string cmd_msg ข้อความ message ที่แสดง
    //@param boolean reset สั่งเป็น true ถ้าต้องยกเลิกคำสั่งเดิมทั้งหมด
    //@return null
    //@access public

    function fetch_record($sql, $obj_filter = '', $cmd_msg = '', $reset = false) {
        if (get_class($obj_filter) == 'OrSql') {
            $cmd_filter = $obj_filter->OP_[cmd_filter]->get();
            $cmd_order = $obj_filter->OP_[cmd_order]->get();
        } else {
            die('fetch_record filter use OrSql Object!');
        }

        $g_my_sec = new OrSec();
        $sql_cmd = new OrSql();
        $val_ = new OrSysvalue();
        $db_event = $val_->db_event;
        /* รับค่าข้อมูลจาก form ได้แก่
          1. หมายเลข Record
          2. จำนวน Record ทั้งหมด
          3. คำสั่ง Filter
          4. Messsage ที่แสดง
          5. คำสั่งการเปลี่ยนเจ้าของ
          6. ข้อมูล Event on_load */

        if ($db_event[cmd_filter] != "") {
            $this->OP_[cmd_filter]->set(str_replace("#", "'", $db_event[cmd_filter]));
        }

        if ($db_event[cmd_msg] != "") {
            $this->OP_[cmd_msg]->set(str_replace("#", "'", $db_event[cmd_msg]));
        }

        if ($db_event[cmd_order] != "") {
            $this->OP_[cmd_order]->set(str_replace("#", "'", $db_event[cmd_order]));
        }

        if ($db_event[chg_owner] == '->') {
            $this->OP_[chg_owner]->set(true);
            $db_event[navigator] = 'Ref';
        }

        $this->OP_[cur_rec]->set($db_event[cur_rec]);
        $this->OP_[total_rec]->set($db_event[total_rec]);

        if ($db_event[on_load] == '') {
            $EV_[EV_db_event] = $db_event;
            $this->OE_on_load($EV_);
            $this->OP_[on_load]->set(1);
        } else {
            $this->OP_[on_load]->set($db_event[on_load] + 1);
        }
        $evt_on_load = new OrFieldHidden("evt_on_load", "evt_form_db[on_load]");
        $this->set_body($evt_on_load->get_tag($this->OP_[on_load]->get()));

        /*         * ********************************************************************* */

        $sql_cmd->set_cmd_sql($sql); //ตรวจสอบคำสั่ง SQL แปลงเป็น Object
        if ($this->OP_[chg_owner]->get()) {
            $this->set_controls(new OrSelectbox("sec_user"));
        } else {
            $this->set_controls(new OrFieldHidden("sec_user"));
        }
        //$this->set_controls(new OrFieldHidden("sec_ip"));
        //$this->set_controls(new OrFieldHidden("sec_script"));
        $txt_cmd_filter = new OrFieldHidden("evt_form_db[cmd_filter]");
        $txt_cmd_order = new OrFieldHidden("evt_form_db[cmd_order]");
        $txt_cmd_msg = new OrFieldHidden("evt_form_db[cmd_msg]");

        //$this->db->get_query($sql);
        if ($reset Or $db_event[navigator] == 'New') {
            $this->OP_[cmd_filter]->set("");
            $this->OP_[cmd_order]->set("");
            $this->OP_[cmd_msg]->set("");
            $this->OP_[total_rec]->set('');
        } else if ($cmd_filter != "" OR $cmd_order != '') {
            $this->OP_[cmd_filter]->set($cmd_filter);
            $this->set_body($txt_cmd_filter->get_tag(str_replace("'", "#", $this->OP_[cmd_filter]->get())));
            $this->OP_[cmd_order]->set($cmd_order);
            $this->set_body($txt_cmd_order->get_tag(str_replace("'", "#", $this->OP_[cmd_order]->get())));
            $this->OP_[cmd_msg]->set($cmd_msg);
            $this->set_body($txt_cmd_msg->get_tag(str_replace("'", "#", $this->OP_[cmd_msg]->get())));
            $this->OP_[total_rec]->set('');
        } else {
            $this->set_body($txt_cmd_filter->get_tag(str_replace("'", "#", $this->OP_[cmd_filter]->get())));
            $this->set_body($txt_cmd_order->get_tag(str_replace("'", "#", $this->OP_[cmd_order]->get())));
            $this->set_body($txt_cmd_msg->get_tag(str_replace("'", "#", $this->OP_[cmd_msg]->get())));
        }

        if ($this->OP_[cmd_filter]->get() != "") {
            $sql_cmd->set_cmd_where($this->OP_[cmd_filter]->get());
        }

        $sql_count_row = $sql_cmd->get_cmd_sql();

        if ($this->OP_[cmd_order]->get() != "") {
            $sql_cmd->set_cmd_order($this->OP_[cmd_order]->get(), ''); //กำหนดการเรียงเป็น '' เพื่อไม่ให้เกิดคำสั่งเรียงซัำ
            $sql = $sql_cmd->get_cmd_sql();
        }

        $sql = $sql_cmd->get_cmd_sql();

        /* ถ้าเป็นการเปิดครั้งแรก หรือ filter ตรวจสอบหน้าทั้งหมด */
        if ($this->OP_[total_rec]->get() == '') {
            //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | this->OP_[total_rec]->get() =".$this->OP_[total_rec]->get()."<br>";
            /* $this->db->get_query($sql);
              $this->OP_[total_rec]->set($this->db->get_total_row()); */
            $this->OP_[total_rec]->set($this->get_total_row($sql_count_row));
        }
        //echo("<b>debug</b> ".__FILE__." | ".__LINE__." | db_event =".$db_event[navigator]."<br>");
        switch ($db_event[navigator]) {
            case "New":
                $this->new_record();
                break;

            case "Delete":
                $this->del_record();
                $this->db->get_query($sql);
                $this->OP_[total_rec]->set($this->db->get_total_row($sql_count_row));
                $this->first_rec($sql);
                break;

            case "Save":
                if ($this->OP_[cur_rec]->get() == '0') {
                    if ($this->is_data_field()) {
                        $this->add_record();
                        //$this->db->get_query($sql);
                        //$this->OP_[total_rec]->set($this->db->get_total_row($sql_count_row));
                        //$this->last_rec($sql);
                        $this->new_record();
                    } else {
                        $this->new_record(true); //กำหนดให้เพื่อจำค่าที่บันทึกเวลาที่คีย์ข้อมูลแล้วกด Ref เรียกข้อมูลใหม่
                    }
                } else {
                    /* บันทึกรายการ */
                    if ($this->is_data_field()) {
                        $this->save_record();
                    }
                    $this->reload($sql);
                }
                break;

            case "First":
                /* เลื่อนไปรายการแรกของข้อมูล */
                $this->first_rec($sql);
                break;

            case "Next":
                /* เลื่อนไปรายการถัดไปของข้อมูล */
                $this->next_rec($sql);
                break;

            case "Prev":
                /* เลื่อนไปรายการก่อนหน้า */
                $this->prev_rec($sql);
                break;

            case "Last":
                /* เลื่อนไปรายการสุดท้ายของข้อมูล */
                $this->db->get_query($sql);
                $this->OP_[total_rec]->set($this->db->get_total_row($sql_count_row));
                $this->last_rec($sql);
                break;

            case "Ref":
                /* แสดงรายการปัจจุบัน */
                if ($this->OP_[cur_rec]->get() > 0) {
                    $this->reload($sql);
                } else {
                    $this->new_record(true); //กำหนดให้เพื่อจำค่าที่บันทึกเวลาที่คีย์ข้อมูลแล้วกด Ref เรียกข้อมูลใหม่
                    //$this->OP_[nav]->set_event($this->OP_[nav]->OP_["btn_new"]->OP_[value]->get());
                }
                break;

            default:

                $this->first_rec($sql);
        }

        return null;
    }

    //เตรียมฟอร์มเพิ่มรายการใหม่
    //@param string sql คำสั่ง SQL
    //@return null
    //@access private

    function new_record($auto_post = false) {
        $g_my_sec = new OrSec();
        $g_my_sec->OP_[sec_user]->set("");
        $EV_[EV_controls] = $this->val_controls[db_field];
        $this->OE_new_record($EV_); // New Event สุชาติ บุญหชัยรัตน์ 9/1/2548
        foreach ($this->controls as $id => $obj) {
            //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | id =".$id."<br>";
            if ($this->controls[$id]->OP_[db_field]->get()) {
                $this->controls[$id]->OP_[auto_post]->set($auto_post);
                //$this->controls[$id]->OP_[default_value]->set(null);
                $this->controls[$id]->OP_[value]->set(null);
            }
        }
        $this->OP_[cur_rec]->set(0);
        $this->controls[sec_user]->OP_[value]->set($g_my_sec->OP_[user]->get());
        $this->set_body($this->controls[sec_user]->get_tag());
        return null;
    }

    //Reload ข้อมูลตามหมายเลข record
    function reload($sql) {
        /* ตรวจสอบค่าของ cur_rec และ total_rec */
        if ($this->OP_[cur_rec]->get() < 1)
            $this->OP_[cur_rec]->set(1);
        if ($this->OP_[total_rec]->get() < 1)
            $this->OP_[total_rec]->set(1);
        /* แสดงรายการเดิม */
        $limit = " LIMIT " . ($this->OP_[cur_rec]->get() - 1) . "," . ($this->OP_[cur_rec]->get());
        $this->update_controls($sql, $limit);
        return null;
    }

    //Next เลื่อนไปรายการถัดไป
    //@param string sql คำสั่ง SQL
    //@return null
    //@access private

    function next_rec($sql) {
        /** ตรวจสอบจำนวนรายการทั้งหมดต้องมากกว่า เลขรายการปัจจุบัน* */
        $go_record = $this->OP_[cur_rec]->get() - 1;
        $total_record = $this->OP_[total_rec]->get();
        if ($this->OP_[cur_rec]->get() < $total_record) {
            if ($go_record < 0) {
                $go_record = 0;
            } else {
                $go_record = $go_record + 1;
            }
            /* เลื่อนไปรายการถัดไป */
            $limit = " LIMIT " . $go_record . " , 1";
            //$this->OP_[nav]->OP_[cur_rec]->OP_[value]->set($this->OP_[cur_rec]->get()+1);
            $this->OP_[cur_rec]->set($go_record + 1);
            $this->update_controls($sql, $limit);
        } else {
            /* แสดงรายการเดิม */
            $this->reload($sql);
        }

        return null;
    }

    //Prev เลื่อนไปรายการก่อนหน้า
    //@param string sql คำสั่ง SQL
    //@return null
    //@access private

    function prev_rec($sql) {
        /*         * ตรวจสอบเลขรายการปัจจุบัน ต้องมากกว่า 1* */
        $go_record = $this->OP_[cur_rec]->get() - 1;
        if ($this->OP_[cur_rec]->get() > 1) {
            /* เลื่อนไปรายการก่อน */
            $go_record = $go_record - 1;
            $limit = " LIMIT " . $go_record . ",1";
            $this->OP_[cur_rec]->set($go_record + 1);
            $this->update_controls($sql, $limit);
        } else {
            /* แสดงรายการเดิม */
            $this->reload($sql);
        }
        return null;
    }

    //Last เลื่อนไปรายการสุดท้าย
    //@param string sql คำสั่ง SQL
    //@return null
    //@access private

    function last_rec($sql) {
        $go_record = $this->OP_[total_rec]->get();
        $go_record = $go_record - 1;
        $limit = " LIMIT " . $go_record . ",1";
        $this->OP_[cur_rec]->set($go_record + 1);
        $this->update_controls($sql, $limit);
        return null;
    }

    //First เลื่อนไปรายการแรก
    //@param string sql คำสั่ง SQL
    //@return null
    //@access private

    function first_rec($sql) {
        $limit = " LIMIT 0,1";
        $this->OP_[cur_rec]->set(1);
        $this->update_controls($sql, $limit);
        return null;
    }

    //ดึงข้อมูลจากฐานข้อมูล แสดงที่ Control value
    function update_controls($sql, $limit) {
        $g_my_sec = new OrSec(); /* ตรวจสอบค่าเกี่ยวกับสิทธิ์การใช้งาน */
        //if($this->OP_[cmd_filter]->get()!="")$sql.="WHERE ((".$this->OP_[cmd_filter]->get()."))";
        /* ตรวจสอบจำนวนหน้าทั้งหมด */
        //$this->db->get_query($sql);
        //$this->OP_[total_rec]->set($this->db->get_total_row());
        /* -------------------------------- */
        $sql.=$limit;
        //die("<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>");
        $this->db->get_query($sql);
        $this->db->get_record();
        $g_my_sec->OP_[sec_user]->set($this->db->record[sec_user]);
        /* แสดงข้อมูลในหน้าจอ */
        if ($g_my_sec->can_read()) {

            foreach ($this->controls as $id => $obj) {
                if ($this->controls[$id]->OP_[db_field]->get()) {
                    $this->controls[$id]->OP_[default_value]->set(null); //ไม่แสดงค่า default_value กรณีใน field ไม่มีข้อมูลอยู่แล้ว
                    $this->controls[$id]->OP_[value]->set($this->db->record[$id]);
                }
            }
        }
        if ($this->OP_[chg_owner]->get()) {
            $this->controls[sec_user]->OP_[option]->set($g_my_sec->get_user_list());
            $this->OP_['sec_status_tag']->set('เลือกเจ้าของที่ต้องการเปลี่ยน : ' . $this->controls[sec_user]->get_tag());
        } else {
            $this->set_body($this->controls[sec_user]->get_tag());
            $this->OP_['sec_status_tag']->set($g_my_sec->get_status());
        }
        $this->db->close_conn();
        return null;
    }

    //กำหนด Kay field ที่ใช้ Update หรือ Delete
    function set_db_key($key_name) {
        if (is_array($key_name)) {
            $this->db_key = $key_name;
        } else {
            $this->db_key[] = $key_name;
        }
        return null;
    }

    function get_sec_user() {
        $sql = "SELECT sec_user FROM " . $this->db_table;
        $sql.=$this->get_where_key() . ";";
        $this->db->get_query($sql);
        if ($this->db->is_error())
            die('ไม่สามารถตรวจสอบเจ้าของข้อมูลได้ กรุณาตรวจสอบกับผู้ดูแลระบบ' . $this->db->show_error());
        $this->db->get_record();
        return $this->db->record[sec_user];
    }

    //จัดการบันทึกข้อมูลเพิ่มใน ฐานข้อมูล ต้องกำหนด table ที่ต้องการ
    function add_record() {
        $g_my_sec = new OrSec(); /* ตรวจสอบค่าเกี่ยวกับสิทธิ์การใช้งาน */
        $EV_[EV_controls] = $this->val_controls[db_field];
        $this->OE_before_add($EV_);
        $this->db->close_conn();
        $sql = "INSERT INTO `" . $this->db_table . "` (";
        /* จัดการค่าที่ได้รับจาก field ใน form */
        $i = 0;
        foreach ($this->val_controls[db_field] as $id => $val) {
            if ($i > 0) {
                $db_fields.=",";
                $db_values.=",";
            }
            $db_fields.="`$id`";
            $db_values.=$this->get_sql_value($id);
            $i+=1;
        }
        $sec_value = " ,'" . $g_my_sec->OP_[ip_remote]->get() . "','" . $g_my_sec->OP_[script_name]->get() . "'";
        $sql.=$db_fields . " ,`sec_ip`,`sec_script`) values (" . $db_values . $sec_value . ");";
        $this->db->get_query($sql);
        //die("<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>");
        if ($this->db->is_error())
            die('ไม่สามารถเพิ่มข้อมูลได้ กรุณาตรวจสอบกับผู้ดูแลระบบ!' . $this->db->show_error());
        $EV_[EV_controls] = $this->val_controls[db_field];
        $EV_[EV_insert_id] = $this->db->get_insert_id();
        $this->OE_after_add($EV_);
        $g_my_sec->activity(__LINE__ . ",เพิ่มข้อมูลตาม " . $sql . ",OK");
        //$this->OP_[total_rec]->set($this->OP_[total_rec]->get()+1);
        //$this->OP_[cur_rec]->set($this->OP_[total_rec]->get());
        return null;
    }

    //จัดการลบข้อมูลในฐานข้อมูล
    function del_record() {
        $g_my_sec = new OrSec(); /* ตรวจสอบค่าเกี่ยวกับสิทธิ์การใช้งาน */
        $EV_[EV_controls] = $this->val_controls[db_field];
        $this->OE_before_delete($EV_);
        $g_my_sec->OP_[sec_user]->set($this->get_sec_user());
        $this->db->close_conn();
        if (!$g_my_sec->can_del())
            die("ไม่สิทธิ์การลบข้อมูล<br>");
        $sql = "DELETE FROM " . $this->db_table;
        $sql.= $this->get_where_key() . ";";
        //die("test" . $this->get_where_key());
        $this->db->get_query($sql);
        if ($this->db->is_error())
            die('ไม่สามารถลบข้อมูลได้ กรุณาตรวจสอบกับผู้ดูแลระบบ' . $this->db->show_error());
        $g_my_sec->activity(__LINE__ . ",ลบข้อมูลตาม " . $sql . ",OK");
        $this->OE_after_delete();
        return null;
    }

    function is_data_field() {
        /* ตรวจสอบความถูกต้องค่าใน controls ที่ส่งมาให้ */
        $ok = true;
        foreach ($this->val_controls[db_field] as $id => $val) {
            if ($this->controls[$id]->OP_[check_null]->get()) {
                if (is_null($val) OR $val == '') {
                    $error_type = 'is_null';
                    $EV_[EV_control_id] = $id;
                    $EV_[EV_control_val] = $val;
                    $EV_[EV_error_type] = $error_type;
                    $this->OE_after_error($EV_);
                    $ok = false;
                }
            }

            if (!$ok)
                break;
        }
        return $ok;
    }

    function save_record() {
        /* จัดการบันทึกแก้ไขในฐานข้อมูล */
        $g_my_sec = new OrSec();
        $EV_[EV_controls] = $this->val_controls[db_field];
        $this->OE_before_save($EV_);
        $g_my_sec->OP_[sec_user]->set($this->get_sec_user());
        $this->db->close_conn();
        $sql = "UPDATE " . $this->db_table . " SET ";
        if (!$g_my_sec->can_save())
            die("ไม่มีสิทธิ์การบันทึกข้อมูล");
        /* จัดการค่าที่ได้รับจาก field ใน form */
        /* กำหนดวิธี SAVE ใหม่ดูจาก controls แทน val_controls 16/6/2547 */
        //$oop = new oop_function();
        $i = 0;
        foreach ($this->controls as $id => $obj) {
            if ($this->is_OP($obj, "db_field")) {
                if ($obj->OP_[db_field]->get()) {
                    if ($i > 0) {
                        $db_values.=",";
                    }
                    $db_values.= "`" . $id . "` = " . $this->get_sql_value($id);
                    $i+=1;
                }
            }
        }
        //$sql_sec = " , `sec_ip` = '" . $g_my_sec->OP_[ip_remote]->get() . "' , `sec_time` = NOW() , `sec_script` = '" . $g_my_sec->OP_[script_name]->get() . "'";
        $sql.=$db_values . $this->get_where_key() . ";";
        //die("<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>");
        $this->db->get_query($sql);
        if ($this->db->is_error())
            die('ไม่สามารถบันทึกข้อมูล ' . $this->db->show_error());
        $g_my_sec->activity(__LINE__ . ",แก้ไขข้อมูลตาม " . $sql . ",OK");
        $EV_[EV_controls] = $this->val_controls[db_field];
        $this->OE_after_save($EV_);
        return null;
    }

    function get_where_key() {
        $i = 0;
        $sql_where = " WHERE ";
        foreach ($this->db_key as $id => $key) {
            if ($i > 0) {
                $sql_where.=" AND ";
            }
            $sql_where.="`" . $key . "`=" . $this->get_sql_value($key);
            $i+=1;
        }
        return $sql_where;
    }

    //กำหนด table ที่ใช้ในการ add update หรือ Deleteข้อมูล
    function set_db_table($value) {
        $this->db_table = $value;
        return null;
    }

    //กำหนด Class Name ให้ Control ตามเงื่อนไขข้อมูล
    //@param string id Control
    //@return null
    //@access public

    function set_class_name($id) {
        if ($this->controls[$id]->OP_[read_only]->get()) {
            $this->controls[$id]->OP_[class_name]->set('read_only');
        } else {
            /* ตรวจสอบว่าเป็นช่องที่ต้องใส่ข้อมูลหรือไม่? */
            if ($this->controls[$id]->OP_[check_null]->get()) {
                $this->controls[$id]->OP_[class_name]->set('input_need');
            } else {
                $this->controls[$id]->OP_[class_name]->set('input_normal');
            }
        }
        return null;
    }

    //function get_nav_tag(){
    //return $this->OP_[nav]->get_tag()." จำนวน ".$this->OP_[total_rec]->get()." รายการ";
    //}
    //ตรวจสอบค่าของ Control ตาม type เพื่อแปลงเป็นข้อมูลให้ Mysql
    function get_sql_value($id) {
        /* เช็คประเภทของข้อมูล */
        if (!is_numeric($this->val_controls[db_field][$id])) {
            /* ตรวจสอบการเป็นข้อมูลวันที่เวลาไทยหรือไม่ */
            $mysql_th_datetime = new OrMySqlThDatetime($this->val_controls[db_field][$id]);
            if ($mysql_th_datetime->is_datetime) {
                return "'" . $mysql_th_datetime->get_mysql_str() . "'";
            } else {
                $mysql_th_date = new OrMySqlThDate($this->val_controls[db_field][$id]);
                if ($mysql_th_date->is_date) {
                    return "'" . $mysql_th_date->get_mysql_str() . "'";
                } else {
                    return "'" . AddSlashes($this->val_controls[db_field][$id]) . "'";
                }
            }
        } else {
            return "'" . AddSlashes($this->val_controls[db_field][$id]) . "'"; // แก้ไขกรณีบันทึกเลข ทศนิยม สุชาติ บุญหชัยรัตน์  14/10/2547
        }
        //return null;
    }

    //สร้าง Tag ส่วนแสดงข้อมูล
    //@return string
    //@access public

    function get_body_tag() {
        $my_table = new OrTable('db_' . $this->OP_[id]->get());
        $my_table->OP_[align_table]->set('center');
        $my_table->OP_[class_name]->set('tbl_body');
        $my_col = 0;
        $control_count = 0;
        $form_col = $this->OP_[column]->get();
        /**
         * หาจำนวนช่องข้อมูลทั้งหมด เพื่อใช้กำหนด Column Row ให้ถูกต้อง
         */
        foreach ($this->controls AS $id => $control) {
            if ($control->OP_[auto_visible]->get()) {
                $control_count++;
            }
        }
        $my_row = ceil($control_count / $form_col);
        foreach ($this->controls AS $id => $control) {

            if ($control->OP_[auto_visible]->get()) {
                if ($control->OP_[type]->get() != 'hidden' AND $control->OP_[id]->get() != 'sec_user') {
                    $my_col++;
                    $control_count--;

                    $my_table->set_col($control->OP_[caption]->get(), "td_caption");
                    $this->set_class_name($id);

                    if ($my_row == 1 && $control_count == 1) {
                        $colspan = (($form_col * 2) - $my_col);
                        $my_table->set_join_cells($control->get_tag() . $control->OP_[description]->get(), $colspan, 0, "td_data");
                    } else {
                        $my_table->set_col($control->get_tag() . $control->OP_[description]->get(), "td_data");
                    }

                    if ($my_col >= $form_col) {
                        $my_table->set_row('tr_body');
                        $my_row--;
                        $my_col = 0;
                    }
                }
            }
        }
        /** ตรวจสอบจำนวน column ว่าครบหรือไม่ เพื่อแก้ไขปัญหา ไม่โชว์บรรทัดสุดท้าย* */
        if ($my_col > 0) {
            $my_table->set_row('tr_body');
            $my_col = 0;
        }
        return $my_table->get_tag();
    }

    //คืนค่า tag แสดง navigator button
    //@param string skin_file ที่อยู่ของ Skin file
    //@return string
    //@access public

    function get_navigator_tag($skin_file = 'Or!Lib/db/form_navigator.html') {
        /* รับค่า Skin ของชุดปุ่่ม */
        $my_value = new OrSkin($skin_file);
        $cur_rec = new OrFieldHidden('evt_form_db[cur_rec]');
        $cur_rec->OP_[value]->set($this->OP_[cur_rec]->get());
        $total_rec = new OrFieldHidden("evt_form_db[total_rec]");
        $total_rec->OP_[value]->set($this->OP_[total_rec]->get());
        $btn_record = new OrButton("btn_record", "evt_form_db[navigator]");
        if ($this->OP_[cur_rec]->get() == 0) {
            $btn_record->OP_[value]->set('Add');
        } else {
            $btn_record->OP_[value]->set('Save');
        }
        $my_value->set_skin_tag('btn_record', $btn_record->get_tag());
        $my_value->set_skin_tag('cur_rec', $cur_rec->get_tag());
        $my_value->set_skin_tag('total_rec', $total_rec->get_tag());
        $my_value->set_skin_tag('description', $this->OP_[cur_rec]->get() . ' of ' . $this->OP_[total_rec]->get());
        return $my_value->get_tag();
    }

    //คืนค่า tag แสดง status ของรายการ
    //@return string
    //@access public

    function get_status_tag() {
        return $this->OP_['sec_status_tag']->get();
    }

    //คืนค่า จำนวนรายการทั้งหมด
    //@return string
    //@access public

    function get_total_row($sql_count_row) {
        $db_page = $this->db;
        $sql_cmd = new OrSql();
        $sql_cmd->set_cmd_sql($sql_count_row);
        $sql_cmd->OP_[cmd_select]->set(' count(*) AS `total_row` ');
        $count_sql = $sql_cmd->get_cmd_sql();
        $db_page->get_query($count_sql);
        if (!$db_page->is_error()) {
            $db_page->get_record();
            $this->OP_[total_rec]->set($db_page->record[total_row]);
        } else {
            $db_page->show_error();
        }

        return $this->OP_[total_rec]->get();
    }

    //Event on class 
    function OE_on_load($EV_) {
        /* $EV_db_event : array ค่าเหตุการณ์ที่เกิดขึ้น */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[on_load]->get());
        return null;
    }

    function OE_new_record($EV_) {
        /* $EV_controls : array ค่า controls */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[new_record]->get());
        return null;
    }

    function OE_before_add($EV_) {
        /* $EV_controls : array ค่า controls */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[before_add]->get());
        return null;
    }

    function OE_after_add($EV_) {
        /* $EV_controls : array ค่า controls
         * *$EV_insert_id : mix ค่า Auto key */
        extract($EV_, EXTR_OVERWRITE);
        $this->OP_[message]->set('เพิ่มรายการใหม่ สำเร็จ');
        eval($this->OE_[after_add]->get());
        return null;
    }

    function OE_before_save($EV_) {
        /* $EV_controls : array ค่า controls */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[before_save]->get());
        return null;
    }

    function OE_after_save($EV_) {
        /* $EV_controls : array ค่า controls */
        extract($EV_, EXTR_OVERWRITE);
        $this->OP_[message]->set('บันทึกรายการ สำเร็จ');
        eval($this->OE_[after_save]->get());
        return null;
    }

    function OE_before_delete($EV_) {
        /* $EV_controls = ค่า controls */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[before_delete]->get());
        return null;
    }

    function OE_after_delete() {
        $this->OP_[message]->set('ลบรายการ สำเร็จ');
        eval($this->OE_[after_delete]->get());
        return null;
    }

    function OE_after_error($EV_) {
        /* เหตุการณ์หลังจากเกิดความผิดพลาด สุชาติ บุญหชัยรัตน์ 27 ธันวาคม 2546 */
        /* $EV_control_id = ชื่อ control */
        /* $EV_control_val = ค่า control */
        /* $EV_error_type = ประเภทความผิดพลาด */
        extract($EV_, EXTR_OVERWRITE);
        $this->OP_[on_error]->set(true);
        $this->OP_[message]->set('เกิดความผิดพลาด');
        eval($this->OE_[after_error]->get());
        return null;
    }

}

?>
