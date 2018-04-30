<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ORR_Controller
 * @package Orr-projects
 * @author Suchart Bunhachirat <suchartbu@gmail.com>
 */
class ORR_Controller extends CI_Controller {

    protected $page_value = ['title' => NULL, 'sign_status' => NULL, 'topic' => NULL];
    protected $acrud;

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('orr_ACRUD');
    }

    /**
     * Create acrud object
     * @param Array $vals
     * @return Object
     */
    protected function get_acrud(array $vals) {
        $this->acrud = new Orr_ACRUD();
        $acrud = $this->acrud;
        $acrud->set_table($vals['table']);
        $acrud->set_subject($vals['subject']);

        $acrud->callback_before_insert(array($this, 'EV_before_insert'));
        $acrud->callback_after_insert(array($this, 'EV_after_insert'));
        $acrud->callback_before_update(array($this, 'EV_before_update'));
        $acrud->callback_after_update(array($this, 'EV_after_update'));
        $acrud->callback_after_delete(array($this, 'EV_after_delete'));

        return $this->acrud;
    }

    /**
     * 
     */
    protected function set_view($output) {
        $view_name = "project_home.php";
        if (!is_array($output)) {
            $output = is_object($output) ? get_object_vars($output) : array();
        }
        $output['page_value'] = $this->page_value;
        $this->load->view($view_name, (array) $output);
    }

    /**
     * 
     * @param Array post value
     * @return Array
     */
    public function EV_before_insert($EV_post) {
        $sign_ = $this->acrud->get_sign_data();
        $EV_post['sec_owner'] = $sign_['user'];
        $EV_post['sec_user'] = $sign_['user'];
        $EV_post['sec_time'] = date("Y-m-d H:i:s");
        $EV_post['sec_ip'] = $sign_['ip_address'];
        $EV_post['sec_script'] = $sign_['script'];
        return $EV_post;
    }

    public function EV_after_delete($EV_primary_key) {
        $EV_log = 'PKEY:' . $EV_primary_key;
        $this->add_activity_post_log($EV_log, 'After_delete');
    }

    public function EV_after_insert($EV_post, $EV_primary_key) {
        $EV_log = array_merge($EV_post, ['PKEY' => $EV_primary_key]);
        $this->add_activity_post_log($EV_log, 'After_insert');
    }

    /**
     * 
     * @param type $EV_post
     * @return type
     */
    public function EV_before_update($EV_post) {
        $sign_ = $this->acrud->get_sign_data();
        $EV_post['sec_user'] = $sign_['user'];
        $EV_post['sec_time'] = date("Y-m-d H:i:s");
        $EV_post['sec_ip'] = $sign_['ip_address'];
        $EV_post['sec_script'] = $sign_['script'];
        return $EV_post;
    }

    /**
     * 
     * @param type $EV_post
     * @return type
     */
    public function EV_after_update($EV_post, $EV_primary_key) {
        $EV_log = array_merge($EV_post, ['PKEY' => $EV_primary_key]);
        $this->add_activity_post_log($EV_log, 'After_update');
    }

    protected function add_activity_post_log($EV_log, $EV_name) {
        if (is_array($EV_log)) {
            foreach ($EV_log as $key => $val) {
                $txt_log .= $key . ':' . $val . ";";
            }
        } else {
            $txt_log = $EV_log;
        }
        $this->acrud->add_activity($EV_name . '=;' . $txt_log);
    }

}
