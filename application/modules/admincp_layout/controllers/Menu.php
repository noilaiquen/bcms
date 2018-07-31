<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Controller {
    private $module = 'admincp_layout';
    private $controller = 'menu';

    public function __construct() {
        parent::__construct();
        $this->load->model('admincp_layout_model');
    }

    public function index() {
        modules::run('admincp/checkPerm', $this->module, 'r');
        $this->breadcrumbs->set('Settings');
        $this->breadcrumbs->set('Menu');
        $this->load->model('admincp_modules/admincp_modules_model');

        $filters = array(
            'site' => 'admin',
            // 'status' => 1,
            'parent' => 0
        );
        $data['menu'] = $this->admincp_layout_model->getMenus($filters);
        $data['modules'] = $this->admincp_modules_model->getModules(array('status' => 1, 'site' => 'admin'));
        $data['module'] = $this->module;
        $data['controller'] = $this->controller;
        $data['title'] = 'Admincp | menu';
        $data['form_action'] = PATH_URL.$this->module.'/'.$this->controller.'/add';
        $data['load_detail'] = PATH_URL.$this->module.'/'.$this->controller.'/ajax_loadDetail';
        $data['edit'] = PATH_URL.$this->module.'/'.$this->controller.'/edit';
        $data['delete'] = PATH_URL.$this->module.'/'.$this->controller.'/delete';
        $data['template'] = 'menu/index';
        $this->load->view($this->template_admin, $data);
    }

    public function add() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        $json = array();
        $validate = $this->validateForm();
        if($validate && !is_array($validate)) {
            if($this->admincp_layout_model->add()) {
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

    public function edit() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        $json = array();
        if($_POST) {
            $result = $this->admincp_layout_model->editMenu();
            if($result) {
                $json['status'] = 1;
                $json['message'] = 'Edit success!';
            } else {
                $json['status'] = 0;
                $json['message'] = 'Edit error!';
            }
        } else {
            $json['status'] = 0;
            $json['message'] = 'No data provided.';
        }
        $this->response->json($json);
    }

    public function ajax_loadContent() {
        modules::run('admincp/checkPerm', $this->module, 'r', true);
        $menu = $this->admincp_layout_model->getMenus(array('site' => 'admin'));
        hierarchy_menu($menu);
    }

    public function ajax_loadDetail() {
        modules::run('admincp/checkPerm', $this->module, 'r', true);
        $json = array();
        if(isset($_GET['menu_id'])) {
            $this->load->model('admincp_modules/admincp_modules_model');
            $menu_id = (int)$this->input->get('menu_id');
            $menu = $this->admincp_layout_model->getMenuByIndex('id', $menu_id);
            $domain_length = strlen(PATH_URL);
            if($menu) {
                $modules_function = explode('|', $menu['modules_function']);
                $self_module = $modules_function[count($modules_function) - 1];
                $href = substr($menu['href'], $domain_length);

                $data['menu'] = array(
                    'id'        => $menu['id'],
                    'name'      => $menu['name'],
                    'slug'      => $menu['slug'],
                    'icon'      => $menu['icon'],   
                    'status'    => $menu['status'],
                    'site'      => $menu['site'],
                    'href'      => $href,
                    'parent_id' => $menu['parent_id'],
                    'modules_function'  => $modules_function,
                    'self_module'       => $self_module
                );
                $data['menus'] = $this->admincp_layout_model->getMenus(array('parent' => 0, 'site' => 'admin', 'except_id' => $menu_id));
                $data['modules'] = $this->admincp_modules_model->getModules(array('status' => 1, 'site' => 'admin'));

                $json['id'] = $menu['id'];
                $json['status'] = 1;
                $json['html'] = $this->load->view('menu/menu_detail', $data, true);
            } else {
                $json['status'] = 0;
                $json['message'] = 'Menu not found.';
            }

            $this->response->json($json);
        }
    }

    public function delete() {
        modules::run('admincp/checkPerm', $this->module, 'd', true);
        $json = array();
        if(isset($_POST['menu_id'])) {
            $menu_id = $this->input->post('menu_id');
            if(!$this->admincp_layout_model->getChildrensMenu($menu_id)){
                if($this->admincp_layout_model->deleteMenu($menu_id)) {
                    $json['status'] = 1;
                    $json['message'] = 'Delete success.';
                } else {
                    $json['status'] = 0;
                    $json['message'] = 'Delete error.';
                }
            } else {
                $json['status'] = 0;
                $json['message'] = 'Denied. This menu contain childrens menu.';
            }
        } else {
            $json['status'] = 0;
            $json['message'] = 'No menu ID provided.';
        }
        $this->response->json($json);
    }

    public function renderMenu() {
        $filters = array(
            'site' => 'admin',
            'status' => 1
        );
        $results = $this->admincp_layout_model->getMenus($filters);
        $menuPermsView = $this->getMenuPermsView();
        $data['menu_hierarchy'] = $this->buildMenuHierarchy($results, $menuPermsView);
        $data['module'] = $this->uri->segment(1);
        $this->load->view('menu/side_menu', $data);
    }

    public function buildMenuHierarchy($menu, $perm_view, $parent_id = 0) {
        $data = array();
        foreach($menu as $item) {
            if($item['parent_id'] == $parent_id) {
                $modules_function = explode('|', $item['modules_function']); 
                if(!empty(array_intersect($modules_function, $perm_view))) {
                    $item['child'] = $this->buildMenuHierarchy($menu, $perm_view, $item['id']);
                    $data[] = $item;
                }
            }
        }
        return $data;
    }

    protected function getMenuPermsView() {
        $account_perms = $_SESSION['account_info']['perms'];
        $menuPermsView = array();
        if(!empty($account_perms)) {
            foreach($account_perms as $module => $perm) {
                if(isset($perm['r'])) {
                    $menuPermsView[] = $module;
                }
            }
        }
        return $menuPermsView;
    }

    protected function validateForm(){
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
                ),
                array(
                    'field' => 'module',
                    'label' => 'Module',
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
