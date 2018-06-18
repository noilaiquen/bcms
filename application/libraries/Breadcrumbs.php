<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Breadcrumbs {
    private $breadcrumbs = array();

    public function set($name = '', $href = '') {
        if(!empty($name)) {
            $this->breadcrumbs[] = array(
                'name' => $name,
                'href' => !empty($href) ? $href : '#'
            );
        }
    }

    public function show() {
        if(!empty($this->breadcrumbs)) {
            echo '<section class="content-header">';
            echo '<div class="container-fluid">';
            echo '<div class="row mb-2">';
            echo '<div class="col-sm-6">';
            $count = count($this->breadcrumbs);
            echo '<ol class="breadcrumb float-sm-left">';

            foreach($this->breadcrumbs as $key => $breadcrumb) {
                if($count - 1 != $key) {
                    echo '<li class="breadcrumb-item">';
                    echo '<a href="#">'.$breadcrumb['name'].'</a>';
                    echo '</li>';
                } else {
                    echo '<li class="breadcrumb-item active">'.$breadcrumb['name'].'</li>';
                }
            }
            echo '</ol>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</section>';
        }
    }
}