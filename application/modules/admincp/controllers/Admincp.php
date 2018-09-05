<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincp extends MY_Controller {
    private $module = 'admincp';
    private $controller = '';
    

    public function __construct() {
        parent::__construct();
        $this->load->model('admincp_model', 'model');
    }

    public function index() {
        modules::run('admincp/checkPerm', $this->module, 'r');
        $this->breadcrumbs->set('Dashboard');
        $data['template'] = 'dashboard';
        $data['title'] = 'Admincp | Dashboard';
        $data['module'] = $this->module;
        $data['controller'] = $this->controller;
        $this->load->view($this->template_admin, $data);
    }
    
    public function login() {
        if($_POST) {
            $json = array();
            $validate = $this->validateForm();
            if($validate && !is_array($validate)) {
                $this->load->model('admincp_accounts/admincp_accounts_model');
                $info = $this->model->getAccountInfo();
                if($info) {
                    $this->session->set_userdata('account_info', $info);
                    $json['status'] = 1;
                    $json['message'] = 'Login success.';
                    $json['redirect_url'] = PATH_URL.$this->module;
                } else {
                    $json['status'] = 0;
                    $json['message'] = 'Username or password incorrectly.';
                }
            } else {
                $json['status'] = 0;
                $json['message'] = array_values($validate);
            }
            $this->response->json($json);
        } else {
            if(!empty($_SESSION['account_info'])){
                redirect(PATH_URL.$this->module);
            } else {
                $data['form_action'] = PATH_URL.$this->module.'/login';
                $this->load->view('admincp/login', $data);
            }
        }
    }

    public function logout() {
        $this->session->unset_userdata('account_info');
        redirect(PATH_URL.$this->module.'/login/');
    }

    public function checkPerm($module, $role, $isAjax = false) {
        $account_info = $_SESSION['account_info'];
        if(isset($_SESSION['account_info']['perms'][$module][$role])) {
            return true;
        } else {
            if($isAjax) {
                $this->response->json(array(
                    'status' => 0,
                    'message' => 'Permission denied.'
                ));
            } else {
                echo 'Permission denied.';
                exit();
            }
        }
    }

    private function validateForm(){
        $this->load->helper('form');
        $this->load->library('form_validation');
    
        $config = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s is required.'
                ),
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s is required.',
                ),
            )
        );
        
        $this->form_validation->set_rules($config);
        if(!$this->form_validation->run()){
            return $this->form_validation->error_array();
        } else {
            return true;
        }
    }
}
