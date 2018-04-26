<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Project
 * ตัวอย่างหน้าจอเริ่มต้น
 * @link Orr-projects/index.php/Project/
 * @author suchart bunhachirat
 */
class Project extends ORR_Controller {

    //private $page_value = ['title' => NULL, 'sign_status' => NULL, 'topic' => NULL];

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
        $this->load->helper('url');
    }
    
    public function index() {
        $this->set_view((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function my_sys() {
        try {
            $crud = new Orr_ACRUD();

            $crud->set_theme('datatables');
            $crud->set_table('my_sys');
            $crud->set_subject('โปรแกรม');
            $crud->columns('sys_id', 'title', 'any_user', 'aut_user');
            $crud->required_fields(array('sys_id', 'any_user', 'aut_user', 'aut_group', 'aut_any', 'aut_god'));
            $crud->default_as('any_use', 1)->default_as('aut_user', 3)->default_as('aut_group', 2)->default_as('aut_any', 1)->default_as('aut_god', 1);
            //$crud->default_as('aut_user', '1');
            $crud->display_as('sys_id', 'รหัส')->display_as('aut_user', 'สิทธ์เจ้าของ')->display_as('title', 'ชื่อโปรแกรม');

            $crud->field_type('any_use', 'dropdown', $this->use_set)->field_type('aut_user', 'dropdown', $this->aut_set)
                    ->field_type('aut_group', 'dropdown', $this->aut_set)->field_type('aut_any', 'dropdown', $this->aut_set)
                    ->field_type('aut_god', 'dropdown', $this->use_set);
            $crud->set_relation('aut_can_from', 'my_sys', '{sys_id}  -  {title}');
            $crud->set_relation('sec_user', 'my_user', '{user}  -  {fname} {lname}');


            $output = $crud->render();

            $this->set_view($output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /**
     * Orr-projects User
     */
    public function my_user() {
        /**
         * กำหนดค่าที่เกี่ยวกับหน้าจอ
         */
        $this->page_value['title'] = "ผู้ใช้งาน";
        
        $crud = new Orr_ACRUD();
        /**
         * 
         */
        $crud->set_subject('ผู้ใช้งาน');
        $crud->set_table('my_user');

        $crud->columns('user', 'fname', 'lname', 'status');

        $crud->default_as('status', '0');

        $crud->field_type('val_pass', 'invisible')->field_type('password', 'password')->field_type('status', 'dropdown', $this->status_set);

        $crud->callback_before_insert(array($this, '_md5_val_pass'));
        $crud->callback_before_update(array($this, '_md5_val_pass'));
        
        $output = $crud->render();

        $this->set_view($output);
    }

    /**
     * Encode password before insert or update (use in callback need access public)
     * @access public
     * @param array $post_array
     * @return Array
     */
    public function _md5_val_pass($post_array) {
        if (!empty($post_array['password'])) {
            $post_array['val_pass'] = md5($post_array['password']);
        }
        unset($post_array['password']);
        return $post_array;
    }

}
