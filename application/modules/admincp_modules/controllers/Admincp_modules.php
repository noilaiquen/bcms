<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincp_modules extends MY_Controller {
    private $module = 'admincp_modules';
    private $controller = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('admincp_modules_model');
    }

    public function index() {
        modules::run('admincp/checkPerm', $this->module, 'r');
        $this->checkModule();
        $this->breadcrumbs->set('Settings');
        $this->breadcrumbs->set('Module');

        $data['module'] = $this->module;
        $data['controller'] = $this->controller;
        $data['title'] = 'Admincp | Module';
        // $data['add_new'] = PATH_URL.$this->module.'/add/';
        $data['template'] = 'index';
        $this->load->view($this->template_admin, $data);
    }

    // public function add() {
    //     modules::run('admincp/checkPerm', $this->module, 'w', true);
    //     if($_POST) {
    //         $json = array();
    //         $validate = $this->validateForm();
    //         if($validate && !is_array($validate)) {
    //             $name_function = trim($this->input->post('name_function'));
    //             $status = (int)$this->input->post('status');
    //             if(!$this->admincp_modules_model->checkExistByIndex('name_function', $name_function)){
    //                 if($this->admincp_modules_model->add($name_function, $status)) {
    //                     $json['status'] = 1;
    //                     $json['message'] = 'Add success!';
    //                 } else {
    //                     $json['status'] = 0;
    //                     $json['message'] = 'Add error!';
    //                 }
    //             } else {
    //                 $json['status'] = 0;
    //                 $json['message'] = 'Module already exist.';
    //             }
    //         } else {
    //             $json['status'] = 0;
    //             $json['message'] = array_values($validate);
    //         }
    //         $this->response->json($json);
    //     } else {
    //         $this->breadcrumbs->set('Settings');
    //         $this->breadcrumbs->set('Module');
    //         $this->breadcrumbs->set('Add new');

    //         $data['module'] = $this->module;
    //         $data['controller'] = $this->controller;
    //         $data['title'] = 'Admincp | Add module';
    //         $data['form_action'] = PATH_URL.$this->module.'/add/';
    //         $data['template'] = 'ajax_editContent';
    //         $this->load->view($this->template_admin, $data);
    //     }
    // }

    // public function edit($id = 0) {
    //     modules::run('admincp/checkPerm', $this->module, 'w', true);
    //     if($_POST) {
    //         $json = array();
    //         $validate = $this->validateForm();
    //         if($validate && !is_array($validate)) {
    //             $name_function = trim($this->input->post('name_function'));
    //             if(!$this->admincp_modules_model->checkExistByIndexAndId('name_function', $name_function, $id)){
    //                 if($this->admincp_modules_model->edit($id)) {
    //                     $json['status'] = 1;
    //                     $json['message'] = 'Edit success!';
    //                 } else {
    //                     $json['status'] = 0;
    //                     $json['message'] = 'Edit error!';
    //                 }
    //             } else {
    //                 $json['status'] = 0;
    //                 $json['message'] = 'Module not found.';
    //             }
    //         } else {
    //             $json['status'] = 0;
    //             $json['message'] = array_values($validate);
    //         }
    //         $this->response->json($json);
    //     } else {
    //         if(!empty($id)) {
    //             $this->breadcrumbs->set('Settings');
    //             $this->breadcrumbs->set('Module');
    //             $this->breadcrumbs->set('Edit');

    //             $data['info'] = $this->admincp_modules_model->getByIndex('id', $id); 
    //             $data['module'] = $this->module;
    //             $data['controller'] = $this->controller;
    //             $data['title'] = 'Admincp | Edit Module';
    //             $data['form_action'] = PATH_URL.$this->module.'/edit/'.$id;
    //             $data['template'] = 'ajax_editContent';
    //             $this->load->view($this->template_admin, $data);
    //         } else {
    //             redirect(PATH_URL.$this->module);
    //         }
    //     }
    // }

    public function ajax_loadContent() {
        modules::run('admincp/checkPerm', $this->module, 'r', true);
        $per_page = $this->input->post('per_page');
        $start = $this->input->post('start');
        $results = $this->admincp_modules_model->getsearchContent($per_page, $start);

        $this->load->library('AdminPagination');
        $config['per_page'] = $per_page;
        $config['start'] = $start;
		$config['num_links'] = 3;
		$config['func_ajax'] = 'searchContent';
        $config['total_rows'] = $total =  $this->admincp_modules_model->getTotalsearchContent();
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
                if($this->admincp_modules_model->delete($ids)){
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
            $this->admincp_modules_model->updateStatus($id, $status);
            $this->load->view('ajax_updateStatus', $data);
        }
    }

    // private function validateForm(){
    //     $result = array();
    //     if($_POST) {
    //         $this->load->helper('form');
    //         $this->load->library('form_validation');
      
    //         $config = array(
    //             array(
    //                 'field' => 'name_function',
    //                 'label' => 'Module',
    //                 'rules' => 'required',
    //                 'errors' => array(
    //                     'required' => '%s is required.'
    //                 ),
    //             )
    //         );
            
    //         $this->form_validation->set_rules($config);
      
    //         if(!$this->form_validation->run()){
    //             $result = $this->form_validation->error_array();
    //         } else {
    //             $result = true;
    //         }
    //     } else {
    //         $result = array('Permission denied.');
    //     }

    //     return $result;
    // }

    private function checkModule() {
        $path = APP_MODULE.'*';
        $list_modules = $this->admincp_modules_model->getModules();
        $list_modules = mapping($list_modules, 'name_function');

        $modules = array();
        foreach (glob($path) as $key => $folder) {
            $module_string = str_replace('\\', '/', $folder);
            $module_array = explode('/', $module_string);
            $count_segment = count($module_array);
            $module = $module_array[$count_segment -1];

            if(empty($list_modules[$module])) {
                $this->admincp_modules_model->add($module, 0, $key+1);
            }
        }
    }
}
