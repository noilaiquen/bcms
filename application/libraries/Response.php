<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Response {
    public function json($json) {
        header('Content-Type: application/json');
        echo json_encode($json);
        exit();
    }

    public function html($html) {
        header('Content-Type: text/html');
        echo $html;
        exit();
    }
}