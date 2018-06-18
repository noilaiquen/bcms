<?php
if (!function_exists('select_box_menu')) {
	function select_box_menu($data,$parent = 0,$str='',$select=0) {
		foreach ($data as $val) {
			$id = $val['menu_id'];
			$name = $val['name'];
			if ($val['parent_id'] == $parent) {
				if ($select != 0 && $id == $select) {
					echo '<option value="'.$id.'" selected="selected">'.$str.' ' .$name.'</option>';
				} else {
					echo '<option value="'.$id.'">'.$str.' ' .$name.'</option>';
				}
				select_box_menu($data,$id,$str.'|---',$select);
			}
		}
	}
}

if (!function_exists('hierarchy_menu')) {
    function hierarchy_menu($data, $parent_id = 0, $char = ''){
        $cate_child = array();
        foreach ($data as $key => $item){
            if ($item['parent_id'] == $parent_id){
                $cate_child[] = $item;
                unset($data[$key]);
            }
        }
        
        if ($cate_child){
            echo '<ul class="nav nav-pills nav-sidebar flex-column menu-open" data-widget="treeview" role="menu" data-accordion="false">';
            foreach ($cate_child as $key => $item){
                if($item['parent_id'] == 0) {
                    echo '<li class="nav-item has-treeview menu-open">';
                    echo '<a href="javascript:void(0)" onclick="removeMenu('.$item['menu_id'].')" class="nav-link active">';
                    echo '<i class="nav-icon fa '.$item['icon'].'"></i>';
                    // echo '<p>'.$char.$item['name'].'</p>';
                    echo '<p>'.$item['name'].'</p>';
                    echo '</a>';
                    echo '</li>';
                } else {
                    echo '<li class="nav-item menu-open">';
                    echo '<a href="javascript:void(0)" onclick="removeMenu('.$item['menu_id'].')" class="nav-link child-active">';
                    echo '<i class="nav-icon fa '.$item['icon'].'"></i>';
                    // echo '<p>'.$char.$item['name'].'</p>';
                    echo '<p>'.$item['name'].'</p>';
                    echo '</a>';
                    echo '</li>';
                }
                hierarchy_menu($data, $item['menu_id'], $char.'|---');
            }
            echo '</ul>';
        }
    }
}

