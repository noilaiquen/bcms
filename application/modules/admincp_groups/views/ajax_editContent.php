<!-- iCheck -->
<link rel="stylesheet" href="<?=PATH_URL?>assets/plugins/iCheck/flat/red.css">
<script src="<?=PATH_URL?>assets/plugins/iCheck/icheck.min.js"></script>
<div class="row">
	<div class="col-md-12">
		<div class="card card-primary" id="cardContent">
			<div class="card-header">
				<h3 class="card-title">Form</h3>
			</div>
			<form role="form" action="<?=$form_action?>" class="form-horizontal" name="form">
				<div class="card-body">
					<div class="form-group row">
                        <label class="col-form-label col-sm-3 text-right" for="name">Name</label>
                        <div class="col-sm-6">
						    <input class="form-control" id="name" name="name" placeholder="Enter group name.." type="text" value="<?=isset($info['name']) ? $info['name'] : ''; ?>" autocomplete="off">
                        </div>
					</div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-right" for="status">Active</label>
                        <div class="col-sm-6">
                            <select name="status" id="status" class="form-control">
                                <option value="1" <?php if(isset($info['status'])&&$info['status']==1) echo 'selected';?>>Active</option>
                                <option value="0" <?php if(isset($info['status'])&&$info['status']==0) echo 'selected';?>>Disable</option>
                            </select>
                        </div>
                    </div>
				</div>
				<div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
        </div>
    </div>
</div>

<?php if(isset($modules)){?>
<div class="row">
	<div class="col-md-12">
		<div class="card" id="cardPerm">
			<div class="card-header">
                <h3 class="card-title">Permission</h3>
			</div>
			<form role="form" action="<?=$form_perm?>" class="form-horizontal" name="formPerm">
				<div class="card-body">
                    <div class="row">
                        <?php if($modules) {
                        foreach($modules as $module) { ?>
                            <div class="col-md-3">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title"><?=$module['name']?></h3>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" value="<?=$info['id']?>" name="group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="perm[<?=$module['name_function']?>][r]" <?=isset($perms[$module['name_function']]['r'])?'checked':'';?>>
                                            <label class="form-check-label" for="exampleCheck1">Read</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="perm[<?=$module['name_function']?>][w]" <?=isset($perms[$module['name_function']]['w'])?'checked':'';?>>
                                            <label class="form-check-label" for="exampleCheck1">Write</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="perm[<?=$module['name_function']?>][d]" <?=isset($perms[$module['name_function']]['d'])?'checked':'';?>>
                                            <label class="form-check-label" for="exampleCheck1">Delete</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-danger" type="submit">Save</button>
                </div>
			</form>
        </div>
    </div>
</div>
<?php } ?>

<script>
    $(document).ready(function(){
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-red',
            increaseArea: '20%' // optional
        });

        $('form[name="form"]').submit(function(event){
            event.preventDefault();
            $.ajax({
                url: this.action,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    name: $('#name').val(),
                    status: $('#status').val(),
                },
                beforeSend: function(){
                    dismissAlert();
                    showLoading();
                },
                complete: function(){
                    hideLoading();
                },
                success: function(json){
                    if(json.status == 1) {
                        showSuccess(json.message);
                        document.getElementsByName('form')[0].reset();
                    } else {
                        showError(json.message);
                    }
                }
            });
        });

        $('form[name="formPerm"]').submit(function(event){
            event.preventDefault();
            $.ajax({
                url: this.action,
                type: 'POST',
                dataType: 'JSON',
                data: $(this).serializeArray(),
                beforeSend: function(){
                    dismissAlert();
                    showLoading('cardPerm');
                },
                complete: function(){
                    hideLoading('cardPerm');
                },
                success: function(json){
                    console.log('---------', json);
                    if(json.status == 1) {
                        showSuccess(json.message);
                    } else {
                        showError(json.message);
                    }
                }
            });
        });
    });
</script>