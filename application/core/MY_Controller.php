<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
/* load the MX_Router class */
require APPPATH . "third_party/MX/Controller.php";
 
/**
 * Description of my_controller
 *
 * 
 */
class MY_Controller extends MX_Controller {
    public $template_admin = 'admincp/master';

    function __construct() {
        parent::__construct();
        // $this->checkModule();
        $admincp_segment = explode('_', $this->uri->segment(1));
        if($admincp_segment[0] == 'admincp' && $this->uri->segment(2) != 'login') {
            if(empty($_SESSION['account_info'])) {
                redirect(PATH_URL.'admincp/login');
            }
        }
    }

    // public function checkModule() {
    //     $path = APP_MODULE.'*';
    //     $this->load->model('admincp_model');
    //     $list_modules = $this->admincp_model->getModules();
    //     $list_modules = mapping($list_modules, 'name_function');

    //     $modules = array();
    //     foreach (glob($path) as $key => $folder) {
    //        $module_string = str_replace('\\', '/', $folder);
    //        $module_array = explode('/', $module_string);
    //        $count_segment = count($module_array);
    //        $module = $module_array[$count_segment -1];

    //        if(empty($list_modules[$module])) {
    //            $this->admincp_model->addModule($module, $key+1);
    //        }
    //     }
    // }
}
 
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */