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
     * @param Array $vars
     * @return Object
     */
    protected function get_acrud(array $vars) {
        $this->acrud = new Orr_ACRUD();
        $acrud = $this->acrud;
        $acrud->set_table($vars['table']);
        $acrud->set_subject($vars['subject']);

        $acrud->callback_before_insert(array($this, 'EV_before_insert'));
        $acrud->callback_before_update(array($this, 'EV_before_update'));
        
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
    public function EV_before_update($EV_post) {
        return $this->EV_before_insert($EV_post);
    }

}
