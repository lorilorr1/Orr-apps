<?php

/**
 * Orr-projects Authorize Class
 * คลาสการตรวจสอบสิทธิ์การเข้าถึงข้อมูล
 * @package Orr-projects
 * @author Suchart Bunhachirat <suchartbu@gmail.com>
 * @version 2561
 */
class Authorize_orr extends CI_Model {

    /**
     * Sign status
     * @var array 
     */
    protected $sign_data = ['id' => 0, 'user' => NULL, 'key' => NULL, 'status' => "Unknown"];

    /**
     * Class construct
     * 
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database('orr-projects');
        if ($this->session->has_userdata('sign_data')) {
            $this->sign_data = json_decode($this->session->userdata('sign_data'), TRUE);
            $this->set_sign_data();
        }
    }

    /**
     * สถานะการเข้าใชัระบบ
     * @return array
     */
    private function set_sign_data() {
        /**
         * เช็คข้อมูลผุ้ใช้งานจากฐานข้อมูล
         */
        $sql = "SELECT * FROM  `my_user`  WHERE  id = ? AND`status` = 0 ";
        $query = $this->db->query($sql, array($this->sign_data['id']));
        echo $this->sign_data['key'];
        if ($query->num_rows() === 1) {
            if ($this->sign_data['key'] === $this->sign_key($query->row()->sec_time)) {
                $this->sign_data['status'] = "Online";
            } else {
                $this->sign_data['status'] = "Unusual";
            }
        }else{
            $this->sign_data['status'] = "Abnormal";
        }
    }

    /**
     * Signin method
     * @param string $user
     * @param string $pass
     * @return string
     */
    public function sign_in($user, $pass) {
        /**
         * ค้นข้อมูลจากชื่อผู้ใช้ รหัสผ่าน และสถานะ
         */
        $sql = "SELECT * FROM  `my_user`  WHERE  user = ? AND val_pass LIKE  ? AND`status` = 0 ";
        $pass = "%" . md5($pass) . "%";
        $query = $this->db->query($sql, array($user, $pass));
        if ($query->num_rows() === 1) {
            $this->sign_data['id'] = $query->row()->id;
            $this->sign_data['user'] = $query->row()->user;
            $this->sign_data['key'] = $this->sign_key($query->row()->sec_time);
            $this->sign_data['status'] = 'Signin';
            $data = json_encode($this->sign_data);
            $this->session->set_userdata("sign_data", $data);
        } else {
            $this->sign_out();
        }
    }

    public function get_sign_data() {
        return $this->sign_data;
    }

    private function sign_key($value) {
        return md5($value);
    }

    /**
     * 
     */
    public function sign_out() {
        $this->session->sess_destroy();
    }

}
