<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincp_groups extends MY_Controller {
    private $module = 'admincp_groups';
    private $controller = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('admincp_groups_model', 'model');
    }

    public function index() {
        modules::run('admincp/checkPerm', $this->module, 'r');
        $this->breadcrumbs->set('Accounts');
        $this->breadcrumbs->set('Group');

        $data['module'] = $this->module;
        $data['controller'] = $this->controller;
        $data['title'] = 'Admincp | Accounts group';
        $data['add_new'] =  PATH_URL.$this->module.'/add/';
        $data['template'] = 'index';
        $this->load->view($this->template_admin, $data);
    }

    public function add() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        if($_POST) {
            $json = array();
            $validate = $this->validateForm();
            if($validate && !is_array($validate)) {
                $name = trim($this->input->post('name'));
                if(!$this->model->checkExistByIndex('name', $name)){
                    if($this->model->add()) {
                        $json['status'] = 1;
                        $json['message'] = 'Add success!';
                    } else {
                        $json['status'] = 0;
                        $json['message'] = 'Add error!';
                    }
                } else {
                    $json['status'] = 0;
                    $json['message'] = 'Group already exist.';
                }
            } else {
                $json['status'] = 0;
                $json['message'] = array_values($validate);
            }
            $this->response->json($json);
        } else {
            $this->breadcrumbs->set('Accounts');
            $this->breadcrumbs->set('Group');
            $this->breadcrumbs->set('Add new');
            
            $data['module'] = $this->module;
            $data['controller'] = $this->controller;
            $data['title'] = 'Admincp | Add group';
            $data['form_action'] = PATH_URL.$this->module.'/add/';
            $data['template'] = 'ajax_editContent';
            $this->load->view($this->template_admin, $data);
        }
    }

    public function edit($id = 0) {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        if($_POST) {
            $json = array();
            $validate = $this->validateForm();
            if($validate && !is_array($validate)) {
                $name = trim($this->input->post('name'));
                if(!$this->model->checkExistByIndexAndId('name', $name, $id)){
                    if($this->model->edit($id)) {
                        $json['status'] = 1;
                        $json['message'] = 'Edit success!';
                    } else {
                        $json['status'] = 0;
                        $json['message'] = 'Edit error!';
                    }
                } else {
                    $json['status'] = 0;
                    $json['message'] = 'Group already exist.';
                }
            } else {
                $json['status'] = 0;
                $json['message'] = array_values($validate);
            }
            $this->response->json($json);
        } else {
            if(!empty($id)) {
                $this->breadcrumbs->set('Accounts');
                $this->breadcrumbs->set('Group');
                $this->breadcrumbs->set('Edit');

                $this->load->model('admincp_modules/admincp_modules_model');

                $data['perms'] = $this->model->getPerms($id);
                $data['modules'] = $this->admincp_modules_model->getModules(array('status' => 1));
                $data['info'] = $this->model->getByIndex('id', $id); 
                $data['module'] = $this->module;
                $data['controller'] = $this->controller;
                $data['title'] = 'Admincp | Edit group';
                $data['form_action'] = PATH_URL.$this->module.'/edit/'.$id;
                $data['form_perm'] = PATH_URL.$this->module.'/addPerms/';
                $data['template'] = 'ajax_editContent';
                $this->load->view($this->template_admin, $data);
            } else {
                redirect(PATH_URL.$this->module);
            }
        }
    }

    public function ajax_loadContent() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        $per_page = $this->input->post('per_page');
        $start = $this->input->post('start');

        $this->load->library('AdminPagination');
        $config['per_page'] = $per_page;
        $config['start'] = $start;
		$config['num_links'] = 3;
		$config['func_ajax'] = 'searchContent';
        $config['total_rows'] = $total =  $this->model->getTotalsearchContent();
        $this->adminpagination->initialize($config);
        $data = array(
            'results' => $this->model->getsearchContent($per_page, $start),
            'total' => $total,
            'start' => $start,
            'module' => $this->module,
            'controller' => $this->controller,
            'edit_url' => PATH_URL.$this->module.'/edit/'
        );
		$this->session->set_userdata('start', $start);
        $this->load->view('ajax_loadContent', $data);
    }

    public function addPerms() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        $json = array();
        if($_POST || $_POST['group']) {
            if($this->model->addPerms()){
                $json['status'] = 1;
                $json['message'] = 'Update success.';
            } else {
                $json['status'] = 0;
                $json['message'] = 'Update error.';
            }
        } else {
            $json['status'] = 0;
            $json['message'] = 'Permission denied.';
        }
        $this->response->json($json);
    }

    public function delete(){
        modules::run('admincp/checkPerm', $this->module, 'd', true);
        $json = array();
        if(!empty($this->input->post('ids'))) {
            $ids = $this->input->post('ids');
            if(!in_array(1, $ids)) {
                if($this->model->delete($ids)){
                    $json['status'] = 1;
                    $json['message'] = 'Success.';
                } else {
                    $json['status'] = 0;
                    $json['message'] = 'Error.';
                }
            } else {
                $json['status'] = 0;
                $json['message'] = 'Permission denied.';
            }
        } else {
            $json['status'] = 0;
            $json['message'] = 'Error.';
        }
        $this->response->json($json);
    }

    public function updateStatus() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        if(!empty($this->input->post('id'))) {
            $id = $this->input->post('id');
            if($this->input->post('status')==0){
				$status = 1;
			}else{
				$status = 0;
			}
            $data['status'] = $status;
            $data['id'] = $id;
            $this->model->updateStatus($id, $status);
            $this->load->view('management/ajax_updateStatus', $data);
        }
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
                    'rules' => 'required|min_length[4]',
                    'errors' => array(
                        'required' => '%s is required.',
                        'min_length' => '%s must more than 3 character.',
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
