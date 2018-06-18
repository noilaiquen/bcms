<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="<?=PATH_URL?>index.php/admincp" class="nav-link <?=$module=='admincp'?'active':'';?>">
                <i class="nav-icon fa fa-dashboard"></i>
                <p>Dashboard</p>
            </a>
        </li>    
        <?php foreach($menu_hierarchy as $child){?>
            <li class="nav-item <?=$child['href']!='#'?'':'has-treeview'?>">
                <a href="<?=$child['href']?>" class="nav-link">
                    <i class="nav-icon fa <?=$child['icon']?>"></i>
                    <p>
                        <?=$child['name'] ?>
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <?php foreach($child['child'] as $sub_child) {?>
                        <li class="nav-item">
                            <a href="<?=$sub_child['href']?>" class="nav-link">
                                <i class="nav-icon fa <?=$sub_child['icon']?>"></i>
                                <p><?=$sub_child['name']?></p>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
    </ul>
</nav>