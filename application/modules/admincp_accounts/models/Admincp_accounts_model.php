<?php
class Admincp_accounts_model extends CI_Model {
	private $table = 'admincp_accounts';
	
    function getsearchContent($limit, $start){
		$this->db->select('admincp_accounts.*, admincp_groups.name as group_name');
		$this->db->from($this->table);
		$this->db->join('admincp_groups', 'admincp_accounts.group_id = admincp_groups.id', 'inner');
		$this->db->limit($limit, $start);
		$this->db->order_by($this->input->post('func_sort'), $this->input->post('type_sort'));

		$this->db->where('admincp_accounts.id != 1');
		if($this->input->post('search_content')!='' && $this->input->post('search_content')!='type here...'){
			$this->db->where('(`username` LIKE "%'.$this->input->post('search_content').'%")');
        }
        if($this->input->post('filter1')!='') {
            $this->db->where('admincp_accounts.status = ', (int)$this->input->post('filter1'));
        }
        if($this->input->post('filter2')!='') {
            $this->db->where('admincp_accounts.group_id = ', (int)$this->input->post('filter2'));
        }
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')==''){
			$this->db->where('admincp_accounts.created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
		}
		if($this->input->post('dateFrom')=='' && $this->input->post('dateTo')!=''){
			$this->db->where('admincp_accounts.created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')!=''){
			$this->db->where('admincp_accounts.created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
			$this->db->where('admincp_accounts.created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
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
			$this->db->where('(`username` LIKE "%'.$this->input->post('search_content').'%")');
        }
        if($this->input->post('filter1')!='') {
            $this->db->where('status = ', (int)$this->input->post('filter1'));
        }
        if($this->input->post('filter2')!='') {
            $this->db->where('group_id = ', (int)$this->input->post('filter2'));
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
			'username' => trim($this->input->post('username')),
			'password' => md5($this->input->post('password')),
			'group_id' => (int)$this->input->post('group'),
			'status' => (int)$this->input->post('status'),
			'created' => date('Y-m-d H:i:s') 
		);
		if($this->db->insert($this->table, $data)){
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

	public function getByIndex($index, $value) {
		$this->db->where($index, $value);
		$query = $this->db->get($this->table);
		
		if($query->row_array()) {
			return $query->row_array();
		}
		return false;
	}

	public function edit($id) {
		$data['status'] = (int)$this->input->post('status');
		$data['group_id'] = (int)$this->input->post('group');

		if(!empty($this->input->post('password'))) {
			$data['password'] = md5($this->input->post('password'));
		}
		$data['updated'] = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		if($this->db->update($this->table, $data)) {
			return true;
		}
		return false;
	}
}