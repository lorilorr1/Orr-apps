<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Sign in page for Orr-apps
 *
 * @author it
 */
class Mark extends CI_Controller {

    private $page_value = NULL;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('authorize_orr');
    }

    /**
     * index :
     * @param String $name Description
     * @return NULL
     */
    public function index() {
        $sign_data = $this->authorize_orr->get_sign_data();
        $this->page_value = ['sign_status' => $sign_data['user'] . " - " . $sign_data['status'], 'title' => "Orr projects Sing in", 'topic' => "Welcome to sign in"];
        if ($sign_data['status'] === 'Online') {
            redirect(site_url('Welcome'));
        }
        $this->set_view();
    }

    /**
     * ตรวจสอบรหัสผู้ใช้งาน จากหน้าจอเข้าระบบ
     */
    public function signin() {
        $this->authorize_orr->sign_in($this->input->post('username'), $this->input->post('password'));
        redirect('Mark');
    }

    public function signout() {
        $this->authorize_orr->sign_out();
        redirect('Welcome');
    }

    private function set_view($view_name = "mark_view") {
        $html_tag_value = ['page_value' => $this->page_value, 'js_files' => array(base_url('assets/jquery/jquery-3.2.1.min.js'), base_url('assets/jquery/jquery-3.2.1.min.js')), 'css_files' => array(base_url('assets/bootstrap/css/bootstrap.min.css'))];
        $this->load->view($view_name, (array) $html_tag_value);
    }

}
