<style>
    a.child-active {
        background-color: #eee !important
    }
</style>
<div class="row">
	<div class="col-md-6">
		<div class="card card-primary">
			<form action="<?=$form_action?>" name="form">
                <div class="card-header no-border">
                    <h3 class="card-title">
						Add new
					</h3>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label for="">Name</label>
						<input type="text" name="name" id="name" class="form-control">
					</div>
					<!-- <div class="form-group">
						<label for="">Href</label>
						<input type="text" name="href" id="href" class="form-control">
					</div> -->
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<button type="button" class="btn btn-default"><?=PATH_URL?></button>
						</div>
						<input type="text" name="href" id="href" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Icon</label>
						<input type="text" name="icon" id="icon" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Parent</label>
						<select name="parent" id="parent" class="form-control">
							<option value="0">No parent</option>
                            <?php select_box_menu($menu) ?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Module</label>
						<select name="module" id="module" class="form-control">
							<?php foreach($modules as $module) { ?>
								<option value="<?=$module['name_function']?>"><?=$module['name']?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="card-footer">
					<input type="submit" class="btn btn-primary" value="Submit" />
				</div>
			</form>
		</div>
	</div>
	<div class="col-md-6">
        <div class="card card-warning" id="cardContent">
            <div class="card-header no-border">
                <h3 class="card-title">
                    Overview
                </h3>
            </div>
            <div class="card-body" id="ajaxLoadContent">
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function () {
        searchContent(0);

		$('form[name="form"]').submit(function (event) {
			event.preventDefault();
			$.ajax({
				url: this.action,
				type: 'POST',
				dataType: 'JSON',
				data: $(this).serializeArray(),
				beforeSend: function() {
					dismissAlert();
					showLoading();
				},
				complete: function() {
					hideLoading();
				},
				success: function(json) {
					if(json.status == 1) {
                        document.getElementsByName('form')[0].reset();
						showSuccess(json.message);
						searchContent(0);
						
					} else {
						showError(json.message);
					}
				}
			});
		});
    });
</script>