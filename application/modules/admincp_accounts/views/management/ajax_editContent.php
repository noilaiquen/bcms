<div class="row">
	<div class="col-md-12">
		<div class="card card-primary" id="cardContent">
			<div class="card-header">
				<h3 class="card-title">Form</h3>
			</div>
			<form role="form" action="<?=$form_action?>" class="form-horizontal" name="form">
				<div class="card-body">
					<div class="form-group row">
                        <label class="col-form-label col-sm-3 text-right" for="username">Username</label>
                        <div class="col-sm-6">
						    <input class="form-control" id="username" name="username" placeholder="Enter username.." type="text" value="<?=isset($info['username']) ? $info['username'] : ''; ?>" <?=isset($info['username']) ? 'disabled' : '' ?> autocomplete="off">
                        </div>
					</div>
					<div class="form-group row">
                        <label class="col-form-label col-sm-3 text-right" for="Password">Password</label>
                        <div class="col-sm-6">
						    <input class="form-control" id="password" name="password" placeholder="Enter password..." type="password" autocomplete="off">
                        </div>
					</div>
					<div class="form-group row">
                        <label class="col-form-label col-sm-3 text-right" for="repassword">Re password</label>
                        <div class="col-sm-6">
						    <input class="form-control" id="repassword" name="repassword" placeholder="Enter password once again..." type="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-right" for="group">Group</label>
                        <div class="col-sm-6">
                            <select name="group" id="group" class="form-control">
                                <?php foreach($groups as $group) {?>
                                    <option value="<?=$group['id']?>" <?php if(isset($info['group_id'])&&$info['group_id']==$group['id']) echo 'selected';?>><?=$group['name']?></option>
                                <?php } ?>
                            </select>
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

<script>
    $(document).ready(function(){
        $('form[name="form"]').submit(function(event){
            event.preventDefault();
            $.ajax({
                url: this.action,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    username: $('#username').val(),
                    password: $('#password').val(),
                    repassword: $('#repassword').val(),
                    group: $('#group').val(),
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
        })
    });
</script>