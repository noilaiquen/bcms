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

        if(isset($filters['modules'])) {
            $this->db->where('modules_function', $filters['modules']);
        }

        if(!empty($filters['except_id'])) {
            $this->db->where('id != ', $filters['except_id']);
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
            $this->addModuleFuction($parent_id, $module);
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

    public function editMenu() {
        $data = array();
        if(!empty($this->input->post('menu_id'))) {
            $id = (int)$this->input->post('menu_id');
            $module = strtolower(trim($this->input->post('module')));
            $parent_id = (int)$this->input->post('parent');
            $old_data = $this->getMenuByIndex('id', $id);
            $old_parent_id = (int)$old_data['parent_id'];
            $old_modules = $old_data['modules_function'];

            if($parent_id != $old_parent_id) {
                /* remove module function in parent old if old_parent_id > 0 */
                $same_module = $this->getMenus(array('parent'=> $old_parent_id, 'modules' => $old_modules));
                if($old_parent_id > 0 && count($same_module) < 2) {
                    $this->removeModuleFunction($old_parent_id, $module);
                }

                /* update module function to parent if has parent */
                if($parent_id > 0) {
                    $this->addModuleFuction($parent_id, $module);
                }
            }

            $data['name'] = !empty(trim($this->input->post('name'))) ? trim($this->input->post('name')) : $old_data['name'];
            $data['slug'] = !empty(trim($this->input->post('name'))) ? strtolower(trim($this->input->post('name'))) : $old_data['slug'];
            $data['href'] = !empty(trim($this->input->post('href'))) ? PATH_URL.trim($this->input->post('href')) : $old_data['href'];
            $data['icon'] = !empty(trim($this->input->post('icon'))) ? trim($this->input->post('icon')) : $old_data['icon'];
            $data['status']             = (int)($this->input->post('status'));
            $data['parent_id']          = $parent_id;
            $data['modules_function']   = $module;
            $data['site']               = 'admin';
            $data['updated']            = date('Y-m-d H:i:s');

            $this->db->where('id', $id);
            return $this->db->update('admincp_menu', $data);
        }
        return false;
    }

    protected function addModuleFuction($menu_id, $module_name) {
        $menu = $this->getMenuByIndex('id', $menu_id);
        if($menu) {
            $modules = !empty($menu['modules_function']) ? explode('|', $menu['modules_function']) : array();
            if(!in_array($module_name, $modules)){
                $modules[] = $module_name;
            }
            $this->db->where('id', $menu_id);
            return $this->db->update('admincp_menu', array('modules_function'=> implode('|', $modules)));
            // return true;
        }
    }

    protected function removeModuleFunction($menu_id, $module_name) {
        $menu = $this->getMenuByIndex('id', $menu_id);
        if($menu) {
            $new_modules = array();
            $old_modules = !empty($menu['modules_function']) ? explode('|', $menu['modules_function']) : array();
            if(!empty($old_modules)) {
                foreach($old_modules as $module) {
                    if($module != $module_name) {
                        $new_modules[] = $module;
                    }
                }
            }
            $this->db->where('id', $menu_id);
            return $this->db->update('admincp_menu', array('modules_function'=> implode('|', $new_modules)));
            // return true;
        }
    }

    public function deleteMenu($menu_id) {
        $this->db->where('id', $menu_id);
        return $this->db->delete('admincp_menu');
    }

    public function getChildrensMenu($parent_id) {
        $this->db->where('parent_id', $parent_id);
        $query = $this->db->get('admincp_menu');
        if($query->result_array()) {
            return $query->result_array();
        }
        return false;
    }
}