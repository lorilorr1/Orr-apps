<?php

/**
 * Orr_projects extends functions from Grocey CRUD
 * API and Functions list
 * Access Create Read Update Delete
 * 
 * @package Orr-projects
 * @author Suchart Bunhachirat <suchartbu@gmail.com>
 * 
 */

/**
 * 
 */
class Orr_ACRUD extends Grocery_CRUD {

    protected $auth_model = null;

    /**
     * default value of input field
     * @var array 
     */
    protected $default_as = [];

    /**
     * 
     */
    protected $sign_data = [];

    /**
     * Initial 
     * Constructor
     *
     * @access  public
     */
    public function __construct() {
        parent::__construct();
        $ci = &get_instance();
        $ci->load->model('Authorize_orr');
        $this->auth_model = new Authorize_orr();
        $this->sign_data = $this->get_sign_data();
        if ($this->sign_data['status'] !== 'Online') {
            redirect(site_url('Mark'));
        }else if(!$this->auth_model->get_sys_exist()){
            /**
             * @todo นำไปหน้าที่ตั้งค่าโปรแกรม
             */
            die('ไม่พบโปรแกรม ' . $this->sign_data['script']);
        }
        $language = 'thai';
        $this->set_language($language);
        $this->field_type('sec_owner', 'invisible')->field_type('sec_user', 'invisible')->field_type('sec_time', 'invisible')->field_type('sec_ip', 'invisible')->field_type('sec_script', 'invisible');
        $sec_fields = ['sec_owner','sec_user','sec_time','sec_ip','sec_script'];
        $this->set_label_as($sec_fields);
         //$this->display_as('sec_owner', 'เจ้าของ')->display_as('sec_user', 'แก้ไขโดย')->display_as('sec_time', 'แก้ไขเมื่อ')->display_as('sec_ip', 'เลขไอพี')->display_as('sec_script', 'แก้ไขจาก');
    }

    /**
     *
     * Default value of the field.
     * field type can use : string , readonly
     * @param $field_name
     * @param $default_as
     * @return void
     */
    public function default_as($field_name, $default_as = null) {

        if (is_array($field_name)) {
            foreach ($field_name as $field => $default_as) {
                $this->default_as[$field] = $default_as;
            }
        } elseif ($default_as !== null) {
            $this->default_as[$field_name] = $default_as;
        }
        return $this;
    }

    /**
     * Return default value when field value is null.
     * @param object $field_info
     * @param $value
     * @return void
     */
    private function get_value($field_info, $value) {
        if (isset($this->default_as[$field_info->name]) && is_null($value)) {
            $value = $this->default_as[$field_info->name];
        }
        return $value;
    }

    /**
     * 
     * Override method for default value.
     */
    protected function get_string_input($field_info, $value) {
        return parent::get_string_input($field_info, $this->get_value($field_info, $value));
    }

    /**
     * 
     * Override method for default value.
     */
    protected function get_readonly_input($field_info, $value) {
        return parent::get_readonly_input($field_info, $this->get_value($field_info, $value));
    }

    /**
     * 
     * Override method for default value.
     */
    protected function get_dropdown_input($field_info, $value) {
        return parent::get_dropdown_input($field_info, $this->get_value($field_info, $value));
    }

    /**
     * 
     * Override method for default value.
     */
    protected function get_integer_input($field_info, $value) {
        return parent::get_integer_input($field_info, $this->get_value($field_info, $value));
    }

    public function get_sign_data() {
        return $this->auth_model->get_sign_data();
    }
    
    public function add_activity($txt){
        $this->auth_model->add_activity($txt);
    }
    
    public function set_label_as(array $fields){
        $rows = $this->auth_model->get_fields_label($fields);
        foreach ($rows as $field_) {
            $this->display_as($field_['field_id'], $field_['name']);
        }
        
        //$this->display_as('sec_owner', 'เจ้าของ')->display_as('sec_user', 'แก้ไขโดย')->display_as('sec_time', 'แก้ไขเมื่อ')->display_as('sec_ip', 'เลขไอพี')->display_as('sec_script', 'แก้ไขจาก');
    }

}
