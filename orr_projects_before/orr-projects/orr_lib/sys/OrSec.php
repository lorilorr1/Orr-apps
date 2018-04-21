<?php

// OrSec.php
//Created on 2-Apr-06
//@author Suchart Bunhachirat
//@version php4 - php5
//
//
//class OrSec
//Class ควบคุมสิทธิ์การใช้งาน

class OrSec extends OrObj {

    private $info = false;

    //
    //กำหนดค่าเริ่มต้นของ Class
    //@param boolean check
    //@return
    //@access public

    function __construct($check = true) {
        global $my_sec_user, $my_sec_key;
        $this->property('user', 'string');
        $this->property('sys_id', 'string');
        $this->property('sys_user', 'string');
        $this->property('sec_user', 'string');
        $this->property('group', 'string');
        $this->property('script_name', 'string');
        $this->property('ip_remote', 'string');
        $this->property('sec_key', 'string');
        $this->property('str_sql', 'string');
        $this->property('title', 'string');
        $this->property('description', 'string');
        $this->property('aut_can_from', 'string'); //การสืบทอดคุณสมบัติสิทธิ์การใช้งาน

        /* เริ่ม กำหนดเหตุการณ์ของ Calss */
        $this->event('login');

        /* ตรวจสอบการกำหนดค่าที่เกี่ยวข้อง */

        $this->OP_[user]->set($my_sec_user);
        $this->OP_[sec_key]->set($my_sec_key);
        $this->set_info();
        if ($check) {
            debug_mode(__FILE__, __LINE__, $check, 'ตรวจสอบสิทธิ์');
            $this->login_check(); //การบันทึกเข้าใช้งาน
            $this->get_protect();
        } else {
            debug_mode(__FILE__, __LINE__, $check, 'ไม่ตรวจสอบสิทธิ์');
        }
    }

    //
    //ข้อมูลที่เกี่ยวข้อง จากฐานข้อมูล
    //@param
    //@return null
    //@access public

    function set_info() {
        global $SCRIPT_FILENAME, $REMOTE_ADDR, $my_cfg_sec;

        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = "SELECT * FROM `my_sys`WHERE sys_id='" . basename($SCRIPT_FILENAME) . "';";
        $db_sec->get_query($sql);
        $db_sec->get_record();
        if ($db_sec->total_error > 0
            )$db_sec->show_error();
        if ($db_sec->record[sys_id] == basename($SCRIPT_FILENAME)) {
            debug_mode(__FILE__, __LINE__, $this->info, 'set_info');
            $this->info = true;
        }
        $this->OP_[script_name]->set(basename($SCRIPT_FILENAME));
        $this->OP_[ip_remote]->set($REMOTE_ADDR);
        $this->OP_[sys_id]->set($db_sec->record[sys_id]);
        $this->OP_[aut_can_from]->set($db_sec->record['aut_can_from']); //การสืบทอดสิทธิ์การใช้งาน
        $this->OP_[title]->set($db_sec->record['title']);
        $this->OP_[description]->set($db_sec->record['description']);

        return null;
    }

    //
    //ตรวจสอบการบันทึกเข้าใช้งาน
    //@param
    //@return null
    //@access public

    function login_check() {
        global $my_sec_user, $my_sec_key, $SCRIPT_FILENAME, $REMOTE_ADDR, $my_cfg_sec, $my_cfg_opt;
        /** $my_sec_user : รหัสผู้ใช้ , $my_sec_key : คีย์ตรวจสอบการใช้งาน * */
        if (isset($my_sec_user) and $my_sec_user != '') {
            debug_mode(__FILE__, __LINE__, $my_sec_user, 'ผู้ใช้ระบบ');
            debug_mode(__FILE__, __LINE__, $my_sec_key, 'คีย์ระบบ');
            debug_mode(__FILE__, __LINE__, md5($my_sec_user . $my_cfg_sec[ki]), 'คีย์ตรวจสอบ');
            /** มีการกำหนด session และ มี user login แล้ว * */
            if (md5($my_sec_user . $my_cfg_sec[ki]) != $my_sec_key) {
                $this->activity(__LINE__ . ',' . $my_sec_user . '|' . $SCRIPT_FILENAME . $REMOTE_ADDR . ',login_check');
                die('login_check ระบบความปลอดภัย ถูกบุกรุก ระบบหยุดทำงาน กรุณาแจ้งผู้ดูแลระบบ ด่วน!');
            }
        } else {
            /** ตรวจการบันทึกเข้าใช้ระบบใหม่ * */
            if ($my_cfg_opt[login_page] != '') {
                header("Location:" . $my_cfg_opt[login_page]);
            }
            die('คุณยังไม่สามารถเข้าใช้โปรแกรม กรุณาบันทึกการเข้าใช้งานก่อน');
        }
        return null;
    }

    //
    //บันทึกเข้าใช้งาน
    //@param string user
    //@param string pass
    //@return null
    //@access public

    function login($user, $pass) {
        global $REMOTE_ADDR, $my_sec_user, $my_sec_key, $my_cfg_sec, $my_cfg_opt;
        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = 'SELECT * FROM `my_user` WHERE `user` = \'' . $user . '\' AND `status` = 0';
        $db_sec->get_query($sql);
        $db_sec->get_record();
        if ($db_sec->total_error > 0
            )$db_sec->show_error();
        if ($db_sec->record[val_pass] == md5($pass)) {
            $this->OP_[user]->set($user);
            $this->OP_[sec_key]->set(md5($user . $my_cfg_sec[ki]));
            $my_sec_user = $this->OP_[user]->get();
            $my_sec_key = $this->OP_[sec_key]->get();
            $this->activity(__LINE__ . ',login user ' . $this->OP_[user]->get() . ',OK');
            $EV_[EV_login] = true;
        } else {
            $this->activity(__LINE__ . ',login user ' . $user . '|' . $pass . ',ERROR');
        }
        $db_sec->close_conn();
        $EV_[EV_user] = $user;
        $this->OE_login($EV_);

        if ($my_cfg_opt[after_login_page] != '') {
            header("Location:" . $my_cfg_opt[after_login_page]);
        }

        return null;
    }

    //
    //ตรวจสอบการลงทะเบียนโปรแกรม
    //@param boolean check
    //@return null
    //@access public

    function get_protect() {
        global $SCRIPT_FILENAME, $my_cfg_opt;
        /* ตรวจสอบว่ามีการลงทะเบียนระบบหรือไม่ */
        debug_mode(__FILE__, __LINE__, $this->info, 'get_protect');
        if (!$this->info) {
            $this->set_info();
        }

        if ($this->OP_[sys_id]->get() == basename($SCRIPT_FILENAME)) {
            $fun_val = true;
        } else {
            die('โปรแกรม' . $SCRIPT_FILENAME . 'ไม่ปรากฎสัญชาติ กรุณาติดต่อ ผู้ดูแลระบบตรวจสอบด่วน!');
        }

        if (!$this->can_use()) {
            if ($my_cfg_opt[error_page] != '') {
                header("Location:" . $my_cfg_opt[error_page] . '?val_msg[description]=' . 'ไม่มีสิทธิ์การใช้งาน ' . $this->OP_[sys_id]->get() . 'กรุณาตรวจสอบสิทธิการใช้โปรแกรมของคุณ!');
            }
            die('ไม่มีสิทธิ์การใช้งาน ' . $this->OP_[sys_id]->get() . ' กรุณาตรวจสอบสิทธิการใช้โปรแกรมของคุณ!');
        }

        return $fun_val;
    }

    //
    //คำสั่งบันทึกออกจากการใช้งาน
    //@param string location
    //@return null
    //@access public

    function logout($location = '') {
        global $PHP_SELF, $my_sec_user, $my_sec_key;
        $my_sec_user = null;
        $my_sec_key = null;
        if ($location == '') {
            $location = $PHP_SELF;
        }
        unset($my_sec_user, $my_sec_key);
        header("Location:" . $location);
        return null;
    }

    //
    //รายการกลุ่มของผู้ใช้งาน
    //@param string user
    //@return null
    //@access public

    function get_group_list($user) {
        global $my_cfg_sec;
        /* ตรวจสอบกลุ่มผู้ใช้งาน  สุชาติ บุญหชัยรัตน์ 23/2/2547 */
        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = "SELECT * FROM `my_group`WHERE `user`='" . $user . "';";
        $db_sec->get_query($sql);
        if ($db_sec->total_error > 0
            )$db_sec->show_error();
        $my_list = array();
        while ($db_sec->get_record()) {
            $my_list[] = $db_sec->record[group];
            debug_mode(__FILE__, __LINE__, $db_sec->record[group], 'กลุ่ม');
        }
        return $my_list;
    }

    //
    //ตรวจสอบสิทธิ์การใช้ข้อมูลกลุ่ม ของเจ้าของข้อมูลกับ กับผู้ใช้งาน
    //คืนค่าเป็น true ถ้าเป็นกลุ่มเดียวกัน
    //@param string sec_user
    //@return boolean
    //@access public

    function chk_aut_group($sec_user) {
        global $my_cfg_sec;
        $fun_val = false;
        /* ตรวจสอบกลุ่มต้องไม่เท่ากับค่าว่าง เพื่อป้องกันการตรวจสอบกลุ่มผิด สุชาติ บุญหชัยรัตน์ 20/9/2548 */
        if ($sec_user != '') {
            /* ตรวจสอบกลุ่มผู้ใช้งาน ของเจ้าของข้อมูล กับผู้ใช้งาน เป็นกลุ่มเดียวหรือไม่ สุชาติ บุญหชัยรัตน์ 23/2/2547 */
            $own_group = $this->get_group_list($sec_user);
            /* ตรวจสอบว่าอยู่ใน กลุ่มของเจ้าของข้อมูลหรืเไม่? */
            $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
            $sql = "SELECT * FROM `my_group`WHERE `user`='" . $this->OP_[user]->get() . "' AND `group`='" . $sec_user . "';";
            //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>";
            $db_sec->get_query($sql);
            $db_sec->get_record();

            if ($db_sec->record[group] == $sec_user) {
                $fun_val = true;
            } else {
                if (count($own_group) > 0) {
                    /* ตรวจสอบว่าอยู่ใน กลุ่มอื่นๆ ของเจ้าของข้อมูลหรือไม่? */
                    foreach ($own_group as $key => $val) {
                        $sql = "SELECT * FROM `my_group`WHERE `user`='" . $this->OP_[user]->get() . "' AND `group`='" . $val . "';";
                        //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | sql =".$sql."<br>";
                        $db_sec->get_query($sql);
                        $db_sec->get_record();
                        if ($db_sec->record[group] == $val) {
                            $fun_val = true;
                            break;
                        }
                    }
                }
            }
        }
        return $fun_val;
    }

    //
    //ตรวจสอบสิทธิ์การใช้ข้อมูล
    //0=ไม่มีสิทธิ์ใช้ข้อมูล;1=มีสิทธิ์อ่านข้อมูล;2=มีสิทธิ์อ่านเขียนข้อมูล;3=มีสิทธิ์อ่านเขียนลบข้อมูล
    //@param string sec_user
    //@return int
    //@access public

    function get_authority() {
        global $my_cfg_sec;
        $this->login_check();
        $fun_val = 0;
        if ($this->OP_[sys_id]->get() != "")/* ตรวจสอบรหัสโปรแกรม */ {
            $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
            $sql = "SELECT * FROM `my_sys`WHERE sys_id='" . $this->OP_[sys_id]->get() . "';";
            $db_sec->get_query($sql);
            $db_sec->get_record();
            if ($db_sec->total_error > 0
                )$db_sec->show_error();
            if ($this->OP_[user]->get() != "" and $db_sec->record[sys_id] == $this->OP_[sys_id]->get()) {
                //if($db_sec->record[any_use] == 0 and $this->OP_[user]->get() != $db_sec->record[sec_user]){
                /* กำหนดตรวจสอบผู้ใช้โปรแกรม */
                //	if(!$this->can_use())die('ไม่มีสิทธิ์การใช้งาน '.$this->OP_[sys_id]->get().'กรุณาตรวจสอบสิทธิการใช้โปรแกรมของคุณ!');;
                //}
                if ($this->OP_[user]->get() == $db_sec->record[sec_user] and $db_sec->record[aut_god] == 1) {
                    /* คืนค่าสิทธิ์เจ้าของข้อมูล */
                    $fun_val = 3;
                } else {
                    /* ตรวจสอบสิทธิ์ในระดับผู้ใช้งานทั่วไป */
                    if ($this->OP_[user]->get() == $this->OP_[sec_user]->get()
                        )$fun_val = $db_sec->record[aut_user];
                    /* ตรวจสอบสิทธิ์ในระดับกลุ่ม */
                    if ($this->chk_aut_group($this->OP_[sec_user]->get())
                        )if ($db_sec->record[aut_group] > $fun_val
                            )$fun_val = $db_sec->record[aut_group];
                    /* ตรวจสอบสิทธิ์ในระดับผู้ใช้งานอื่นๆ */
                    if ($db_sec->record[aut_any] > $fun_val
                        )$fun_val = $db_sec->record[aut_any];
                }
            }
        }
        //$db_sec->close_conn();
        unset($db_sec);
        return $fun_val;
    }

    //
    //ตรวจสอบสิทธิ์การเข้าใช้โปรแกรม
    //คืนค่าเป็น true ถ้าสามารถใช้งานได้
    //@return boolean
    //@access public

    function can_use() {
        global $my_cfg_sec;
        /* ตรวจสอบสิทธิการเข้าใช้งานโปรแกรม สุชาติ บุญหชัยรัตน์ 9/2/2547 */
        $fun_val = false;
        if ($this->OP_[sys_id]->get() != "") {
            if ($this->OP_[aut_can_from]->get() != '') {
                /* ตรวจสอบการสืบทอดสิทธิ์การใช้งาน */
                $sys_id = $this->OP_[aut_can_from]->get();
            } else {
                $sys_id = $this->OP_[sys_id]->get();
            }
            $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
            $sql = "SELECT * FROM `my_can` WHERE `sys_id`='" . $sys_id . "' AND `user`='" . $this->OP_[user]->get() . "';";
            $db_sec->get_query($sql);
            $db_sec->get_record();
            //if($db_sec->total_error>0)$db_sec->show_error();
            if ($db_sec->record[user] == $this->OP_[user]->get()) {
                /* สิทธิ์ผู้ใช้งานที่สามารถใช้ได้ ตามปกติ */
                $this->OP_[str_sql]->set(ltrim($db_sec->record['str_sql']));
                $fun_val = true;
            } else {

                $sql = "SELECT * FROM `my_sys`WHERE sys_id='" . $sys_id . "';";
                $db_sec->get_query($sql);
                $db_sec->get_record();
                if ($db_sec->record[any_use] == 1) {
                    $fun_val = true;
                } else if ($this->OP_[user]->get() == $db_sec->record[sec_user]) {
                    $fun_val = true;
                } else if ($this->chk_aut_group($can_user = $this->can_use_group($sys_id))) {
                    /* ตรวจสิทธิ์ระดับกลุ่มผู้ใช้งาน */
                    $sql = "SELECT * FROM `my_can` WHERE `sys_id`='" . $sys_id . "' AND `user`='" . $can_user . "';";
                    $db_sec->get_query($sql);
                    $db_sec->get_record();
                    //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | str_sql =" . $db_sec->record['str_sql'] . "<br>";
                    $this->OP_[str_sql]->set($db_sec->record['str_sql']);
                    $fun_val = true;
                }
            }
        }
        return $fun_val;
    }

    function can_use_group($sys_id = '') {
        global $my_cfg_sec;
        /* หาสิทธิ์ของโปรแกรม กลุ่มตามผู้ใช้งานปัจจุบัน */

        $my_vaule = '';
        $sql = "SELECT `my_can`.`sys_id` AS `sys_id` , `my_group`.`group` AS `group` , `my_can`.`aut_to_group` AS `aut_to_group` , `my_group`.`user` AS `user` ";
        $sql .= "FROM `my_can`, `my_group` ";
        $sql .= "WHERE ( `my_can`.`user` = `my_group`.`group` ) AND ( ( `my_can`.`sys_id` = '" . $sys_id . "' AND `my_can`.`aut_to_group` = 1 AND `my_group`.`user` = '" . $this->OP_[user]->get() . "' ) )";

        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $db_sec->get_query($sql);
        if ($db_sec->get_record()) {
            $my_value = $db_sec->record['group'];
        }
        //echo "<b>debug</b> ".__FILE__." | ".__LINE__." | my_value=" . $my_value . "<br>";
        return $my_value;
    }

    function can_read() {
        /* ตรวจสอบสิทธิ์การอ่านข้อมูล
          $sys_id=รหัสโปรแกรมที่ตรวจสอบ
          $sec_user=รหัสผู้ใช้งานที่เป็นเจ้าของข้อมูล */
        if ($this->get_protect()) {
            if ($this->get_authority($this->OP_[sys_id]->get(), $this->OP_[sec_user]->get()) > 0) {
                /* ต้องกำหนดระดับสิทธิ์>0 */
                $fun_val = true;
            }
        } else {
            /* ไม่มีการกำหนดการควบคุมข้อมูล */
            $fun_val = true;
        }
        return $fun_val;
    }

    function can_save() {
        /* ตรวจสอบสิทธิ์การบันทึกข้อมูล
          $sys_id=รหัสโปรแกรมที่ตรวจสอบ
          $sec_user=รหัสผู้ใช้งานที่เป็นเจ้าของข้อมูล */
        $fun_val = false;
        if ($this->get_protect()) {
            if ($this->get_authority($this->OP_[sys_id]->get(), $this->OP_[sec_user]->get()) > 1) {
                /* ต้องกำหนดระดับสิทธิ์>1 */
                $fun_val = true;
            }
        } else {
            /* ไม่มีการกำหนดการควบคุมข้อมูล */
            $fun_val = true;
        }
        return $fun_val;
    }

    function can_del() {
        /* ตรวจสอบสิทธิ์การลบข้อมูล
          $sys_id=รหัสโปรแกรมที่ตรวจสอบ
          $sec_user=รหัสผู้ใช้งานที่เป็นเจ้าของข้อมูล */
        $fun_val = false;
        if ($this->get_protect()) {
            if ($this->get_authority($this->OP_[sys_id]->get(), $this->OP_[sec_user]->get()) > 2) {
                /* ต้องกำหนดระดับสิทธิ์>2 */
                $fun_val = true;
            }
        } else {
            /* ไม่มีการกำหนดการควบคุมข้อมูล */
            $fun_val = true;
        }
        return $fun_val;
    }

    function get_status() {
        $tag_chg_owner = '<button name="evt_form_db[chg_owner]" type="submit" value="->" title="แก้ไขเจ้าของข้อมูล">' . $this->OP_[sec_user]->get() . '</button>';
        return 'ผู้ใช้ระบบ : ' . $this->OP_[user]->get() . ' | เจ้าของ : ' . $tag_chg_owner . ' | ระบบ : ' . $this->OP_[sys_id]->get() . ' | ระดับสิทธิ์ : ' . $this->get_authority();
    }

    function activity($description) {
        global $my_cfg_sec, $REMOTE_ADDR, $SCRIPT_FILENAME;
        $this->OP_[script_name]->set(basename($SCRIPT_FILENAME));
        $this->OP_[ip_remote]->set($REMOTE_ADDR);
        $this->OP_[sys_id]->set(basename($SCRIPT_FILENAME));
        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = "INSERT INTO `my_activity` (`id`, `description`, `sec_user`, `sec_time`, `sec_ip`, `sec_script`) VALUES ('', '" .
                AddSlashes($description) . "', '" . $this->OP_[user]->get() . "', NOW(), '" . $this->OP_[ip_remote]->get() . "', '" . $this->OP_[script_name]->get() . "');";
        $db_sec->get_query($sql);
        if ($db_sec->is_error()
            )$db_sec->show_error();
        return null;
    }

    function get_user_list() {
        global $my_cfg_sec;
        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = "SELECT `id` , `user` FROM  `my_user` ORDER BY `user` ASC ;";
        $db_sec->get_query($sql);
        if ($db_sec->total_error > 0
            )$db_sec->show_error();
        $owner_list = array();
        while ($db_sec->get_record()) {
            $owner_list[$db_sec->record[user]] = $db_sec->record[user];
        }
        return $owner_list;
    }

    function get_user_text() {
        /* คืนรายละเอียดผู้ใช้ทั่วไปตาม user สุชาติ บุญหชัยรตน์ 21/12/2546 */
        global $my_cfg_sec;
        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = "SELECT `prefix` , `fname`, `lname` FROM  `my_user` WHERE `user` = '" . $this->OP_[user]->get() . "';";
        $db_sec->get_query($sql);
        if ($db_sec->total_error > 0
            )$db_sec->show_error();
        if ($db_sec->get_record()) {
            $value = $db_sec->record[prefix] . $db_sec->record[fname] . ' ' . $db_sec->record[lname];
        }
        return $value;
    }

    function get_sys_list() {
        global $my_cfg_sec;
        $db_sec = new OrMysql($my_cfg_sec[db]); //(กำหนด Object ฐานข้อมูลที่จะใช้)
        $sql = "SELECT `sys_id` FROM  `my_sys`;";
        $db_sec->get_query($sql);
        if ($db_sec->total_error > 0
            )$db_sec->show_error();
        $sys_list = array();
        while ($db_sec->get_record()) {
            $sys_list[$db_sec->record[sys_id]] = $db_sec->record[sys_id];
        }
        return $sys_list;
    }

    //Event on class
    function OE_login($EV_) {
        /* $EV_user : string user login */
        /* $EV_login : true if login */
        extract($EV_, EXTR_OVERWRITE);
        eval($this->OE_[login]->get());
        return null;
    }

}

?>