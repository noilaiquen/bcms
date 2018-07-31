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
        <div class="card card-primary" id="cardContent">
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

<div class="modal" id="detail_modal">
    <input type="hidden" name="hidden_id" value="0">
    <form action="<?=$edit?>" method="POST" name="form-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <!-- ajax load here -->
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-danger" onclick="drop()">Drop</button>
                    <button type="button" class="btn" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
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

        $('form[name="form-edit"]').submit(function (event) {
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
                    $('.modal-body').html('');
				},
				success: function(json) {
					if(json.status == 1) {
                        $('#detail_modal').modal('hide');
						showSuccess(json.message);
						searchContent(0);
					} else {
						showError(json.message);
					}
				}
			});
		});
    });

    function loadDetail(menu_id) {
        $.ajax({
            url: '<?=$load_detail?>',
            type: 'GET',
            dataType: 'JSON',
            data: {
                menu_id: menu_id
            },
            beforeSend: function() {
                dismissAlert();
                $('input[name="hidden_id"]').val(0);
                $('.modal-body').html('');
            },
            complete: function() {
            },
            success: function(json) {
                if(json.status == 1) {
                    $('input[name="hidden_id"]').val(json.id);
                    $('.modal-body').html(json.html);
                    $('#detail_modal').modal();
                } else {
                    showError(json.message);
                }
            }
        });
    }

    function drop() {
        $.post('<?=$delete?>', { menu_id: $('input[name="hidden_id"]').val()}, function(json) {
            if(json.status == 1) {
                showSuccess(json.message);
                searchContent(0);
            } else {
                showError(json.message);
            }
            $('#detail_modal').modal('hide');
        }, 'JSON')
    }
</script>