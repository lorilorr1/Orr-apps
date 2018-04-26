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
    }
}
