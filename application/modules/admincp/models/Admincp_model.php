<?php
class Admincp_model extends CI_Model {
    public function getModules($filters = array()){
        $this->db->select('*');
        if(isset($filters['status'])) {
            $this->db->where('status', (int)$filters['status']);
        }
        $this->db->order_by('sort','ASC');
        $query = $this->db->get('admincp_modules');
        if($query->result_array()) {
            return $query->result_array();
        }
        return false;
    }

    public function addModule($module_name, $sort = 0) {
        $data = array();
        $data['name'] = ucwords(str_replace('_', ' ', $module_name));
        $data['name_function'] = $module_name;
        $data['status'] = 0;
        $data['sort'] = $sort;
        $data['created'] = getNow();
        $this->db->insert('admincp_modules', $data);
    }

    public function getModule($module_id = 0) {
        $this->db->where('module_id', (int)$module_id);
        $module = $this->db->get('admincp_modules')->row_array();

        if($module) {
            return $module;
        } 
        return false;
    }

    function checkModules($module){
		$this->db->select('module_id,name');
		$this->db->where('name_function',$module);
        $query = $this->db->get('admincp_modules');
        
		if($query->row_array()){
			return $query->row_array();
		}else{
			return false;
		}
    }
    
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