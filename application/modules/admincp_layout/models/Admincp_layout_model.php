<?php
class Admincp_layout_model extends CI_Model {

    public function getMenus($filters = array()) {
        $this->db->select('*');
        if(!empty($filters['site'])) {
            $this->db->where('site', $filters['site']);
        }

        if(isset($filters['status'])) {
            $this->db->where('status', (int)$filters['status']);
        }

        if(isset($filters['parent'])) {
            $this->db->where('parent_id', (int)$filters['parent']);
        }
        $this->db->order_by('menu_id');

        $results = $this->db->get('admincp_menu')->result_array();
        return $results;
    }

    public function add() {
        $data = array();
        
        $data['name'] = trim($this->input->post('name'));
        $data['slug'] = trim($this->input->post('name'));
        $data['href'] = !empty(trim($this->input->post('href'))) ? PATH_URL.trim($this->input->post('href')) : '#';
        $data['icon'] = !empty(trim($this->input->post('icon'))) ? trim($this->input->post('icon')) : 'fa-circle-o';
        $data['parent_id'] = $this->input->post('parent') ? (int)$this->input->post('parent') : 0;
        $data['status'] = 1;
        $data['site'] = 'admin';
        $data['created'] = date('Y-m-d H:i:s');
        if($this->db->insert('admincp_menu', $data)){
            return true;
        }
        return false;
    }
}