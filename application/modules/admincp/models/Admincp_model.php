<?php
class Admincp_model extends CI_Model {
    
    public function getAccountInfo() {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $this->db->select('admincp_accounts.*');
        $this->db->from('admincp_accounts');
        $this->db->join('admincp_groups', 'admincp_accounts.group_id = admincp_groups.id', 'inner');
        $this->db->where('admincp_accounts.username', $username);
        $this->db->where('admincp_accounts.password', $password);
        $this->db->where('admincp_accounts.status', 1);
        $this->db->where('admincp_groups.status', 1);
        $query = $this->db->get();

        if($query->row_array()){
            $account = $query->row_array();
            $this->db->where('group_id', (int)$account['group_id']);
            $query2 = $this->db->get('admincp_perms');
            if($query2->result_array()) {
                foreach($query2->result_array() as $perm) {
                    if(!isset($perms[$perm['module_function']])) {
                        $perms[$perm['module_function']] = array();
                    }
                    $perms[$perm['module_function']][$perm['role']] = $perm['role'];
                }
                $account['perms'] = $perms;
            }
            return $account;
        }
        return false;
        
    }
}