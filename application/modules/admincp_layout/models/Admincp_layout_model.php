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
        $this->db->order_by('id');

        $results = $this->db->get('admincp_menu')->result_array();
        return $results;
    }

    public function add() {
        $data = array();
        $module = strtolower(trim($this->input->post('module')));
        $parent_id = (int)$this->input->post('parent');

        /* update module function to parent if has parent */
        if($parent_id > 0) {
            $parent = $this->getMenuByIndex('id', (int)$this->input->post('parent'));
            if($parent) {
                $parent_modules = !empty($parent['modules_function']) ? explode('|', $parent['modules_function']) : array();
                if(!in_array($module, $parent_modules)){
                    $parent_modules[] = $module;
                }
                $this->db->where('id', $parent_id);
                $this->db->update('admincp_menu', array('modules_function'=> implode('|', $parent_modules)));
            }
        }
        
        $data['name'] = trim($this->input->post('name'));
        $data['slug'] = trim($this->input->post('name'));
        $data['href'] = !empty(trim($this->input->post('href'))) ? PATH_URL.trim($this->input->post('href')) : '#';
        $data['icon'] = !empty(trim($this->input->post('icon'))) ? trim($this->input->post('icon')) : 'fa-circle-o';
        $data['parent_id'] = $parent_id;
        $data['modules_function'] = $module;
        $data['status'] = 1;
        $data['site'] = 'admin';
        $data['created'] = date('Y-m-d H:i:s');
        if($this->db->insert('admincp_menu', $data)){
            return true;
        }
        return false;
    }

    public function getMenuByIndex($index, $value) {
        $this->db->where($index, $value);
        $query = $this->db->get('admincp_menu');

        if($query->row_array()) {
            return $query->row_array();
        }
        return false;
    }
}