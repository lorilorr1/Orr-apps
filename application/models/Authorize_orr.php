<?php

/**
 * Orr-projects Authorize Class
 * คลาสการตรวจสอบสิทธิ์การเข้าถึงข้อมูลจากข้อมูลผู้ใช้งาน และสภาวะการเข้าใช้ระบบ
 * @package Orr-projects
 * @author Suchart Bunhachirat <suchartbu@gmail.com>
 * @version 2561
 */
class Authorize_orr extends CI_Model {

    /**
     * List of all sign data
     * @var array 
     */
    protected $sign_data = ['id' => 0, 'user' => NULL, 'ip_address' => NULL, 'key' => NULL, 'status' => NULL];

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database('orr-projects');
        //$this->uri_data = $this->get_uri_data();
        /**
          if ($this->session->has_userdata('uri_data')) {

          } else {

          $this->session->set_userdata("uri_data", $this->uri_data);
          }
         * 
         */
    }

    /**
     * คืนค่าสถานะการลงชื่อใช้งาน
     * @return Array
     */
    public function get_sign_data() {
        if ($this->session->has_userdata('sign_data')) {
            $this->set_sign();
        }
        return $this->sign_data;
    }

    /**
     * Checking user password when signin
     * 
     * @access public
     * @param string user name
     * @param string password
     */
    public function sign_in($user, $pass) {
        /**
         * ค้นข้อมูลจากชื่อผู้ใช้ รหัสผ่าน และสถานะ
         */
        $sql = "SELECT * FROM  `my_user`  WHERE  user = ? AND val_pass LIKE  ? AND`status` = 0 ";
        $pass = "%" . md5($pass) . "%";
        $query = $this->db->query($sql, array($user, $pass));
        if ($query->num_rows() === 1) {
            /**
             * Create sing key with ip,user,sec_time
             */
            $this->sign_data['id'] = $query->row()->id;
            $this->sign_data['user'] = $query->row()->user;
            $this->sign_data['ip_address'] = $this->get_sign_ip_address();
            $this->sign_data['key'] = $this->get_sign_key($query->row()->sec_time);
            /**
             * Create property
             */
            $this->sign_data['status'] = $this->get_sign_status(TRUE);
            $data = json_encode($this->sign_data);
            $this->session->set_userdata('sign_data', $data);
        } else {
            $this->sign_out();
        }
    }

    /**
     * ตรวจสอบสถานะการลงชื่อเข้าใช้ระบบ
     */
    private function set_sign() {
        $this->sign_data = json_decode($this->session->userdata('sign_data'), TRUE);
        $sql = "SELECT * FROM  `my_user`  WHERE  id = ? AND`status` = 0 ";
        $query = $this->db->query($sql, array($this->sign_data['id']));
        if ($query->num_rows() === 1) {
            if ($this->sign_data['key'] === $this->get_sign_key($query->row()->sec_time)) {
                $this->sign_data['status'] = $this->get_sign_status(TRUE);
            } else {
                $this->sign_data['status'] = $this->get_sign_status(FALSE);
            }
        } else {
            die('Data record is abnormal.');
        }
    }

    /**
     * คืนค่าเป็นจริง ถ้ามีลงชื่อเข้าระบบแล้ว
     * @return boolean
     */
    public function get_sign() {
        if ($this->session->has_userdata('sign_data')) {
            $this->set_sign();
            $val = TRUE;
        } else {
            $val = FALSE;
        }
        return $val;
    }

    /**
     * Return Singin key
     * 
     * @access private
     * @param string Key value for create code
     * @return string
     */
    private function get_sign_key($value) {
        return md5($this->sign_data['ip_address'] . $this->sign_data['user'] . $value);
    }

    /**
     * List of sign status
     * @access public
     * @return String
     */
    public function get_sign_status($is_sign) {
        if ($is_sign) {
            $this->sign_data['status'] = 'Online';
        } else {
            $this->sign_data['status'] = 'Offline';
        }
        return $this->sign_data['status'];
    }

    public function get_sign_ip_address() {
        $ci_input = new CI_Input();
        return $this->sign_data['ip_address'] = $ci_input->ip_address();
    }

    public function sign_out() {
        $this->session->sess_destroy();
    }

}
