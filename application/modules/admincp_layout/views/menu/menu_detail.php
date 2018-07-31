<input type="hidden" name="menu_id" value="<?=$menu['id']?>">
<div class="form-group">
    <label for="">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="<?=$menu['name']?>">
</div>
<div class="input-group mb-3">
    <div class="input-group-prepend">
        <button type="button" class="btn btn-default"><?=PATH_URL?></button>
    </div>
    <input type="text" name="href" id="href" class="form-control" value="<?=$menu['href']?>">
</div>
<div class="form-group">
    <label for="">Icon</label>
    <input type="text" name="icon" id="icon" class="form-control" value="<?=$menu['icon']?>">
</div>
<div class="form-group">
    <div class="row">
        <?php if($menu['parent_id'] != 0){?>
            <div class="col-md-6">
                <label for="module">Module</label>
                <select name="module" id="module" class="form-control">
                    <?php foreach($modules as $module) { ?>
                        <option value="<?=$module['name_function']?>" <?=($menu['self_module']==$module['name_function'])?'selected':'';?>><?=$module['name']?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } else {?>
            <input type="hidden" name="module" value="<?=$menu['self_module']?>">
        <?php } ?>
        <div class="<?=$menu['parent_id'] == 0 ? 'col-md-12' : 'col-md-6';?>">
            <label for="">Parent</label>
            <select name="parent" id="parent" class="form-control">
                <!-- <option value="0">No parent</option> -->
                <?php select_box_menu($menus, 0, '', $menu['parent_id']) ?>
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="">Site</label>
            <select name="site" id="site" class="form-control" disabled>
                <option value="admin" <?=$menu['site'] == 'admin' ? 'selected' : '';?>>Admin</option>
                <option value="client" <?=$menu['site'] == 'client' ? 'selected' : '';?>>Client</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="0" <?=$menu['status'] == 0 ? 'selected' : '';?>>Disable</option>
                <option value="1" <?=$menu['status'] == 1 ? 'selected' : '';?>>Active</option>
            </select>
        </div>
    </div>
</div>