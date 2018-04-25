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

    public $auth_model = null;

    /**
     * default value of input field
     * @var array 
     */
    protected $default_as = [];

    /**
     * 
     */

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
        $sign_data = $this->auth_model->get_sign_data();
        if ($sign_data['status'] !== 'Online') {
            redirect(site_url('Mark'));
        }
        $this->field_type('id', 'readonly')->field_type('sec_time', 'readonly')->field_type('sec_ip', 'readonly')->field_type('sec_script', 'readonly');
        $this->default_as(['sec_ip' => $this->auth_model->get_sign_ip_address(), 'sec_time' => date("Y-m-d H:i:s")]);
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

}
