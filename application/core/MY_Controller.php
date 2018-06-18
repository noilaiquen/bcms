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
        $admincp_segment = explode('_', $this->uri->segment(1));
        if($admincp_segment[0] == 'admincp' && $this->uri->segment(2) != 'login') {
            if(empty($_SESSION['account_info'])) {
                redirect(PATH_URL.'admincp/login');
            }
        }
    }
}
 
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */