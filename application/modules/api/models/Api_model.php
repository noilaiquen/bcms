<?php
class Api_model extends CI_Model {
    public function userLogin($username, $password) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $query = $this->db->get();

        if($query->row_array()) {
            return $query->row_array();
        }
        return false;
    }

    public function writeLog($data) {
        $this->db->insert('log_login', $data);
    }
}