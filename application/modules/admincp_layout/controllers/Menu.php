<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Controller {
    private $module = 'admincp_layout';
    private $controller = 'menu';

    public function __construct() {
        parent::__construct();
        $this->load->model('admincp_layout_model', 'model');
    }

    public function index() {
        $this->breadcrumbs->set('Settings');
        $this->breadcrumbs->set('Menu');

        $filters = array(
            'site' => 'admin',
            // 'status' => 1,
            'parent' => 0
        );
        $data['menu'] = $this->model->getMenus($filters);
        $data['module'] = $this->module;
        $data['controller'] = $this->controller;
        $data['title'] = 'Admincp | menu';
        $data['form_action'] = PATH_URL.$this->module.'/'.$this->controller.'/add';
        $data['template'] = 'menu/index';
        $this->load->view($this->template_admin, $data);
    }

    public function add() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        $json = array();
        $validate = $this->validateForm();
        if($validate && !is_array($validate)) {
            if($this->model->add()) {
                $json['status'] = 1;
                $json['message'] = 'Add success!';
            } else {
                $json['status'] = 0;
                $json['message'] = 'Add error!';
            }
        } else {
            $json['status'] = 0;
            $json['message'] = array_values($validate);
        }
        $this->response->json($json);
    }

    public function ajax_loadContent() {
        $filters = array(
            'site' => 'admin',
            // 'status' => 1,
            // 'parent' => 0
        );
        $menu = $this->model->getMenus($filters);
        hierarchy_menu($menu);
    }

    public function renderMenu() {
        $filters = array(
            'site' => 'admin',
            'status' => 1
        );
        $results = $this->model->getMenus($filters);
        $data['menu_hierarchy'] = $this->buildMenuHierarchy($results);
        $data['module'] = $this->uri->segment(1);
        $this->load->view('menu/side_menu', $data);
    }

    public function buildMenuHierarchy($menu, $parent_id = 0) {
        $data = array();
        foreach($menu as $item) {
            if($item['parent_id'] == $parent_id) {
                $item['child'] = $this->buildMenuHierarchy($menu, $item['menu_id']);
                $data[] = $item;
            }
        }
        return $data;
    }

    private function validateForm(){
        $result = array();
        if($_POST) {
            $this->load->helper('form');
            $this->load->library('form_validation');
      
            $config = array(
                array(
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'required',
                    'errors' => array(
                        'required' => '%s is required.',
                    ),
                )
            );
            
            $this->form_validation->set_rules($config);
      
            if(!$this->form_validation->run()){
                $result = $this->form_validation->error_array();
            } else {
                $result = true;
            }
        } else {
            $result = array('Permission denied.');
        }

        return $result;
    }
}
