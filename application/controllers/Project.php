<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Project
 * ตัวอย่างหน้าจอเริ่มต้น
 * @link Orr-projects/index.php/Project/
 * @author suchart bunhachirat
 */
class Project extends ORR_Controller {

    /**
     * Project Page for this controller.
     * @todo Home Page for Orr projects.
     */
    private $use_set = ['0' => '0 ระบุ', '1' => '1 ไม่ระบุ'];
    private $aut_set = ['0' => '0 ไม่ได้', '1' => '1 อ่านได้', '2' => '2 เขียนได้', '3' => '3 ลบได้'];
    private $status_set = ['0' => '0 Active', '1' => '1 Inactive'];

    public function __construct() {
        parent::__construct();
        $this->load->database('orr-projects');
    }

    public function index() {
        $this->set_view((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function my_sys() {

        /**
         * กำหนดค่าที่เกี่ยวกับหน้าจอ
         */
        $this->page_value['title'] = "โปรแกรม";

        $crud = $this->get_acrud(['table' => 'my_sys', 'subject' => 'โปรแกรม']);

        $crud->columns('sys_id', 'title', 'any_use', 'aut_user', 'aut_group', 'aut_any', 'aut_god','aut_can_from');
        $crud->unique_fields(array('sys_id', 'title'));
        $crud->required_fields(array('sys_id', 'title', 'any_use', 'aut_user', 'aut_group', 'aut_any', 'aut_god'));
        $crud->default_as('any_use', 1)->default_as('aut_user', 3)->default_as('aut_group', 2)->default_as('aut_any', 1)->default_as('aut_god', 1);
        $crud->display_as('sys_id', 'รหัส')->display_as('aut_user', 'สิทธ์เจ้าของ')->display_as('title', 'ชื่อโปรแกรม');
        $crud->field_type('any_use', 'dropdown', $this->use_set)->field_type('aut_user', 'dropdown', $this->aut_set)
                ->field_type('aut_group', 'dropdown', $this->aut_set)->field_type('aut_any', 'dropdown', $this->aut_set)
                ->field_type('aut_god', 'dropdown', $this->use_set);
        $crud->set_relation('aut_can_from', 'my_sys', '{sys_id}  -  {title}');
        /**
         * End of function
         */
        $this->set_view($crud->render());
    }

    /**
     * Orr-projects User
     */
    public function my_user() {
        /**
         * กำหนดค่าที่เกี่ยวกับหน้าจอ
         */
        $this->page_value['title'] = "ผู้ใช้งาน";

        $crud = $this->get_acrud(['table' => 'my_user', 'subject' => 'ผู้ใช้งาน']);
        $crud->columns('user', 'fname', 'lname', 'status');
        $crud->required_fields(array('user', 'fname', 'lname', 'status'));
        $crud->default_as('status', '0');
        $crud->field_type('val_pass', 'invisible')->field_type('password', 'password')->field_type('status', 'dropdown', $this->status_set);
        //$crud->unset_delete();
        /**
         * End of function
         */
        $this->set_view($crud->render());
    }
    
    /**
     * Orr-apps my_datafield
     */
    public function my_datafield(){
         /**
         * กำหนดค่าที่เกี่ยวกับหน้าจอ
         */
        $this->page_value['title'] = "คำจำกัดความข้อมูล";
        $crud = $this->get_acrud(['table' => 'my_datafield', 'subject' => 'คำจำกัดความข้อมูล']);
        $crud->columns('field_id', 'name', 'description');
        $crud->required_fields(array('field_id', 'name'));
        $crud->unique_fields(array('name'));
         /**
         * End of function
         */
        $this->set_view($crud->render());     
    }
    
    public function my_activity(){
        /**
         * กำหนดค่าที่เกี่ยวกับหน้าจอ
         */
        $this->page_value['title'] = "กิจการของระบบ";
        $crud = $this->get_acrud(['table' => 'my_activity', 'subject' => 'กิจกรรมของระบบ']);
        $crud->unset_add()->unset_clone()->unset_edit()->unset_delete();
         /**
         * End of function
         */
        $this->set_view($crud->render());     
    }

    /**
     * MD5 Encode password
     * @param Array Post array
     * @return Array
     */
    protected function md5_pass($EV_post) {
        if (!empty($EV_post['password'])) {
            $EV_post['val_pass'] = md5($EV_post['password']);
        }
        unset($EV_post['password']);
        return $EV_post;
    }

    /**
     * Encode password before insert or update (use in callback need access public)
     * @access public
     * @param Array $EV_post
     * @return Array
     */
    public function EV_before_insert($EV_post) {
        return parent::EV_before_insert($this->md5_pass($EV_post));
    }

    /**
     * Encode password before update (use in callback need access public)
     * @access public
     * @param Array $EV_post
     * @return Array
     */
    public function EV_before_update($EV_post) {
        return parent::EV_before_update($this->md5_pass($EV_post));
    }

}
