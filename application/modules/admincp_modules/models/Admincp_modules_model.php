<?php
class Admincp_modules_model extends CI_Model {
	private $table = 'admincp_modules';
	
    function getsearchContent($limit, $start){
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->limit($limit, $start);
		$this->db->order_by($this->input->post('func_sort'), $this->input->post('type_sort'));
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
	
	public function add($module, $status = 1, $sort = 0) {
		$data = array();
        $data['name'] = ucwords(str_replace('_', ' ', $module));
        $data['name_function'] = $module;
        $data['status'] = $status;
        $data['sort'] = $sort;
        $data['created'] = getNow();
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

	public function edit($id) {
		$data['name_function'] = trim($this->input->post('name_function'));
		$data['name'] =  ucwords(str_replace('_', ' ', $data['name_function']));
		// $data['status'] = (int)$this->input->post('status');
		$this->db->where('id', $id);
		if($this->db->update($this->table, $data)) {
			return true;
		}
		return false;
	}
	
    public function getModules($filters = array()){
        $this->db->select('*');
        if(isset($filters['status'])) {
            $this->db->where('status', (int)$filters['status']);
        }
        if(isset($filters['site'])) {
            $this->db->where('site', $filters['site']);
        }
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);
        if($query->result_array()) {
            return $query->result_array();
        }
        return false;
    }
}