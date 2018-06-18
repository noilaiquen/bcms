<?php
class Admincp_groups_model extends CI_Model {
    private $table = 'admincp_groups';

    function getsearchContent($limit, $start){
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->limit($limit, $start);
		$this->db->order_by($this->input->post('func_sort'), $this->input->post('type_sort'));

		if(isset($_SESSION['account_info']) && $_SESSION['account_info']['id'] != 1) {
			$this->db->where('id != 1');
		}
		if($this->input->post('search_content')!='' && $this->input->post('search_content')!='type here...'){
			$this->db->where('(`name` LIKE "%'.$this->input->post('search_content').'%")');
        }
        if($this->input->post('filter1')!='') {
            $this->db->where('status = ', (int)$this->input->post('filter1'));
        }
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')==''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
		}
		if($this->input->post('dateFrom')=='' && $this->input->post('dateTo')!=''){
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')!=''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
        }
        $query = $this->db->get();
        // p($this->db->last_query(),1);

		if($query->result()){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	function getTotalsearchContent(){
        $this->db->select('*');
		if($this->input->post('search_content')!='' && $this->input->post('search_content')!='type here...'){
			$this->db->where('(`name` LIKE "%'.$this->input->post('search_content').'%")');
        }
        if($this->input->post('filter1')!='') {
            $this->db->where('status = ', (int)$this->input->post('filter1'));
        }
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')==''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
		}
		if($this->input->post('dateFrom')=='' && $this->input->post('dateTo')!=''){
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')!=''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		$query = $this->db->count_all_results($this->table);

		if($query > 0){
			return $query;
		}else{
			return false;
		}
    }

	public function getGroups($filters = array()){
		if(isset($filters['status'])) {
			$this->db->where('status', (int)$filters['status']);
        }
        if(!empty($filters['ignore_id'])) {
            $this->db->where('id !=', (int)$filters['ignore_id']);
        }
        $query = $this->db->get($this->table);
        
        if($query->result_array()){
            return $query->result_array();
        }
        return false;
    }

    public function delete($ids = array()) {
        $this->db->where_in('id', $ids);
        if($this->db->delete($this->table)){
            return true;
        }
        return false;
    } 

    public function updateStatus($id, $status) {
        $this->db->where('id', $id);
        $data['status'] = $status;
        if($this->db->update($this->table, $data)){
            return true;
        }
        return false;
	}
	
	public function add() {
		$data = array(
			'name' => ucwords(trim($this->input->post('name'))),
			'status' => (int)$this->input->post('status'),
			'created' => date('Y-m-d H:i:s') 
		);
		if($this->db->insert($this->table, $data)){
			return true;
		}
		return false;
	}

	public function edit($id) {
		$data['status'] = (int)$this->input->post('status');
		$data['name'] = ucwords(trim($this->input->post('name')));
		$data['updated'] = date('Y-m-d H:i:s');
		$this->db->where('id', (int)$id);
		if($this->db->update($this->table, $data)) {
			return true;
		}
		return false;
	}

	public function checkExistByIndex($index, $value){
		if(is_array($index) && is_array($index) && count($index) == count($value)) {
			foreach($index as $key => $ind) {
				$this->db->where($ind, $value[$key]);
			}
		} else {
			$this->db->where($index, $value);
		}
        $query = $this->db->get($this->table);
		if($query->row_array()){
			return true;
		}
		return false;
    }
    
	public function checkExistByIndexAndId($index, $value, $id){
        $this->db->where($index, $value);
        $this->db->where('id != ', $id);
        $query = $this->db->get($this->table);
		if($query->row_array()){
			return true;
		}
		return false;
	}

	public function getByIndex($index, $value) {
		$this->db->where($index, $value);
		$query = $this->db->get($this->table);
		
		if($query->row_array()) {
			return $query->row_array();
		}
		return false;
	}

	public function addPerms() {
		$group_id = (int)$this->input->post('group');
		$perms = $this->input->post('perm');
		/* delete all fisrt */ 
		$this->db->where('group_id', $group_id);
		$this->db->delete('admincp_perms');

		$this->load->model('admincp_model');
		$modules = mapping($this->admincp_model->getModules(), 'name_function', 'id');

		if(!empty($perms) && is_array($perms)) {
			$data = array();
			foreach($perms as $name_function => $perm) {
				foreach($perm as $role => $val) {
					$array_perm = array(
						'group_id' => $group_id,
						'module_function' => $name_function,
						'module_id' => isset($modules[$name_function]) ? $modules[$name_function] : 0,
						'role' => trim($role)
					);
					$data[] = $array_perm;
				}
			}
			
			if($this->db->insert_batch('admincp_perms', $data)){
				return true;
			} else {
				return fasle;
			}
		}
	}

	public function getPerms($group_id) {
		$this->db->where('group_id', $group_id);
		$query = $this->db->get('admincp_perms');

		if($query->result_array()) {
			$data = array();
			foreach($query->result_array() as $perm) {
				if(!isset($data[$perm['module_function']])) {
					$data[$perm['module_function']] = array();
				}
				$data[$perm['module_function']][$perm['role']] = $perm['role'];
			}
			return $data;
		}
		return false;
	}
}