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

    public function __construct() {
        parent::__construct();
        $this->load->library('grocery_CRUD');
        $this->load->library('orr_ACRUD');
    }

    protected function set_view($output) {
        $view_name = "project_home.php";
        if (!is_array($output)) {
            $output = is_object($output) ? get_object_vars($output) : array();
        }
        $output['page_value'] = $this->page_value;
        $this->load->view($view_name, (array) $output);
    }

}
