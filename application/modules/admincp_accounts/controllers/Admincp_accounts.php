<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincp_accounts extends MY_Controller {
    private $module = 'admincp_accounts';
    private $controller = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('admincp_accounts_model');
    }

    public function index() {
        modules::run('admincp/checkPerm', $this->module, 'r');
        $this->breadcrumbs->set('Accounts');
        $this->breadcrumbs->set('Mangements');

        $this->load->model('admincp_groups/admincp_groups_model');
        $data['groups'] = $this->admincp_groups_model->getGroups();
        $data['module'] = $this->module;
        $data['controller'] = $this->controller;
        $data['title'] = 'Admincp | Accounts managements';
        $data['add_new'] = PATH_URL.$this->module.'/add/';
        $data['template'] = 'index';

        $this->load->view($this->template_admin, $data);
    }

    public function add() {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        if($_POST) {
            $json = array();
            $validate = $this->validateForm();
            if($validate && !is_array($validate)) {
                if(!$this->admincp_accounts_model->checkExistByIndex('username',trim($this->input->post('username')))){
                    if($this->admincp_accounts_model->add()) {
                        $json['status'] = 1;
                        $json['message'] = 'Add success!';
                    } else {
                        $json['status'] = 0;
                        $json['message'] = 'Add error!';
                    }
                } else {
                    $json['status'] = 0;
                    $json['message'] = 'Account has taken.';
                }
            } else {
                $json['status'] = 0;
                $json['message'] = array_values($validate);
            }
            $this->response->json($json);
        } else {
            $this->breadcrumbs->set('Accounts');
            $this->breadcrumbs->set('Mangements');
            $this->breadcrumbs->set('Add new');
            $this->load->model('admincp_groups_model');

            $filter_group = array(
                'status' => 1,
                'ignore_id' => 1
            );

            $data['groups'] = $this->admincp_groups_model->getGroups($filter_group);
            $data['module'] = $this->module;
            $data['controller'] = $this->controller;
            $data['title'] = 'Admincp | add account';
            $data['form_action'] = PATH_URL.$this->module.'/add/';
            $data['template'] = 'ajax_editContent';
            $this->load->view($this->template_admin, $data);
        }
    }

    public function edit($id = 0) {
        modules::run('admincp/checkPerm', $this->module, 'w', true);
        if($_POST) {
            $json = array();
            $validate = $this->validateFormEdit();
            if($validate && !is_array($validate)) {
                $indexs = array('id', 'username');
                $values = array($id, trim($this->input->post('username')));
                if($this->admincp_accounts_model->checkExistByIndex($indexs, $values)){
                    if($this->admincp_accounts_model->edit($id)) {
                        $json['status'] = 1;
                        $json['message'] = 'Edit success!';
                    } else {
                        $json['status'] = 0;
                        $json['message'] = 'Edit error!';
                    }
                } else {
                    $json['status'] = 0;
                    $json['message'] = 'Account not found.';
                }
            } else {
                $json['status'] = 0;
                $json['message'] = array_values($validate);
            }
            $this->response->json($json);
        } else {
            if(!empty($id)) {
                $this->breadcrumbs->set('Accounts');
                $this->breadcrumbs->set('Mangements');
                $this->breadcrumbs->set('Edit');
                $this->load->model('admincp_groups/admincp_groups_model');

                $filter_group = array(
                    'status' => 1,
                    // 'ignore_id' => 1
                );

                $data['groups'] = $this->admincp_groups_model->getGroups($filter_group);
                $data['info'] = $this->admincp_accounts_model->getByIndex('id', $id); 
                $data['module'] = $this->module;
                $data['controller'] = $this->controller;
                $data['title'] = 'Admincp | edit account';
                $data['form_action'] = PATH_URL.$this->module.'/edit/'.$id;
                $data['template'] = 'ajax_editContent';
                $this->load->view($this->template_admin, $data);
            } else {
                redirect(PATH_URL.$this->module.'/'.$this->controller);
            }
        }
    }

    public function ajax_loadContent() {
        modules::run('admincp/checkPerm', $this->module, 'r', true);
        $per_page = $this->input->post('per_page');
        $start = $this->input->post('start');
        $results = $this->admincp_accounts_model->getsearchContent($per_page, $start);

        $this->load->library('AdminPagination');
        $config['per_page'] = $per_page;
        $config['start'] = $start;
		$config['num_links'] = 3;
		$config['func_ajax'] = 'searchContent';
        $config['total_rows'] = $total =  $this->admincp_accounts_model->getTotalsearchContent();
        $this->adminpagination->initialize($config);

        $data = array(
            'results' => $results,
            'total' => $total,
            'start' => $start,
            'module' => $this->module,
            'controller' => $this->controller,
            'edit_url' => PATH_URL.$this->module.'/edit/'
        );
		$this->session->set_userdata('start', $start);
        $this->load->view('ajax_loadContent', $data);
    }

    public function delete(){
        modules::run('admincp/checkPerm', $this->module, 'd', true);
        $json = array();
        if(!empty($this->input->post('ids'))) {
            $ids = $this->input->post('ids');
            if(!in_array(1, $ids)) {
                if($this->admincp_accounts_model->delete($ids)){
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
            $this->admincp_accounts_model->updateStatus($id, $status);
            $this->load->view('ajax_updateStatus', $data);
        }
    }

    private function validateForm(){
        $result = array();
        if($_POST) {
            $this->load->helper('form');
            $this->load->library('form_validation');
      
            $config = array(
                array(
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'required|min_length[4]',
                    'errors' => array(
                        'required' => '%s is required.',
                        'min_length' => '%s must more than 3 character.',
                    ),
                ),
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'required|min_length[6]|max_length[64]',
                    'errors' => array(
                        'required' => '%s is required.',
                        'min_length' => '%s must 6 to 64 character.',
                        'max_length' => '%s must 6 to 64 character.',
                    ),
                ),
                array(
                    'field' => 'repassword',
                    'label' => 'Password confirm',
                    'rules' => 'required|matches[password]',
                    'errors' => array(
                        'required' => '%s is required.',
                        'matches' => 'Two password not match.',
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

    private function validateFormEdit(){
        $result = array();
        if($_POST) {
            $this->load->helper('form');
            $this->load->library('form_validation');
      
            $config = array(
                array(
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'required|min_length[4]',
                    'errors' => array(
                        'required' => '%s is required.',
                        'min_length' => '%s must more than 3 character.',
                    ),
                ),
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'min_length[6]|max_length[64]',
                    'errors' => array(
                        'min_length' => '%s must 6 to 64 character.',
                        'max_length' => '%s must 6 to 64 character.',
                    ),
                ),
                array(
                    'field' => 'repassword',
                    'label' => 'Password confirm',
                    'rules' => 'matches[password]',
                    'errors' => array(
                        'matches' => 'Two password not match.',
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
