<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {
    private $module = 'api';
    private $controller = 'login';

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model', 'model');
        $this->load->library('Jwt', 'JWT');
    }

    public function index() {
        $post = json_decode(file_get_contents('php://input'), true);

        if(!empty($post['username']) && !empty($post['password'])) {
            // write log
            $this->write_log($post);
            $username = trim($post['username']);
            $password = $post['password'];
            $result = $this->model->userLogin($username, $password);
            if($result) {
                $token = array();
                $token['uid']       = $result['id'];
                $token['username']  = $result['username'];
                $token['fullname']  = $result['fullname'];
                $token['email']     = $result['email'];
                $token['level']     = $result['level'];
                $token['iat']       = $iat = time();
                $token['exp']       = $iat + 60;
                $jwt = JWT::encode($token, JWT_SECRET_KEY);
                
                $this->response->json(array(
                    'status'    => 1,
                    'token'     => $jwt,
                    'payload'  => array(
                        'username'  => $result['username'],
                        'fullname'  => $result['fullname'],
                        'email'     => $result['email'],
                        'level'     => $result['level']
                    )
                ));
            } else {
                $this->response->json(array(
                    'status' => 0, 
                    'message' => 'Username or password invalid!'
                ));
            }
        } else {
            $this->response->json(array(
                'status' => 0, 
                'message' => 'Username or password can not be null!'
            ));
        }
    }

    public function write_log($info = array()){
        $data['username'] = $info['username'];
        $data['ip'] = '';
        $data['created'] = date('Y-m-d H:i:s');

        $this->model->writeLog($data);

    }

    public function verify_token() {
        $post = json_decode(file_get_contents('php://input'), true);

        if(!empty($post['token']) || !empty($_SERVER['HTTP_TOKEN'])) {
            if(!empty($_SERVER['HTTP_TOKEN'])) {
                $token = $_SERVER['HTTP_TOKEN'];
            } else {
                $token = $post['token'];
            }

            try {
                $payload = JWT::decode($token, JWT_SECRET_KEY, true);
                if($payload->exp < time()) {
                    $this->response->json(array(
                        'status' => 0,
                        'message' => 'Token expire!'
                    ));
                } else {
                    $this->response->json(array(
                        'status' => 1,
                        'payload' => $payload
                    ));
                }
            } catch(Exception $e) {
                $this->response->json(array(
                    'status' => 0,
                    'message' => $e->getMessage()
                ));
            }
        } else {
            $this->response->json(array(
                'status' => 0,
                'message' => 'Token not found!'
            ));
        }
    }
}