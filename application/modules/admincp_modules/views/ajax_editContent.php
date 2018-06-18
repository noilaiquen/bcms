<div class="row">
	<div class="col-md-12">
		<div class="card card-primary" id="cardContent">
			<div class="card-header">
				<h3 class="card-title">Form</h3>
			</div>
			<form role="form" action="<?=$form_action?>" class="form-horizontal" name="form">
				<div class="card-body">
					<div class="form-group row">
                        <label class="col-form-label col-sm-3 text-right" for="name_function">Module</label>
                        <div class="col-sm-6">
						    <input class="form-control" id="name_function" name="name_function" placeholder="Enter module.." type="text" value="<?=isset($info['name_function']) ? $info['name_function'] : ''; ?>" autocomplete="off">
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
                    name_function: $('#name_function').val(),
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
    });
</script>