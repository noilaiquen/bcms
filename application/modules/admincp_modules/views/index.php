<input type="hidden" value="<?=DEFAULT_FUNC?>" id="func_sort" />
<input type="hidden" value="<?=DEFAULT_SORT?>" id="type_sort" />
<input type="hidden" value="<?=isset($start) ? $start : 0; ?>" id="start" />

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search_content">Search</label>
                            <input type="text" class="form-control" name="search_content" id="search_content" placeholder="Entert search..." onkeypress="return enterSearch(event)">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filter1">Status</label>
                            <select name="filter1" id="filter1" class="form-control" onchange="searchContent(0)">
                                <option value="">--Select--</option>
                                <option value="0">Disable</option>
                                <option value="1">Enable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dateFrom">From date</label>
                            <input type="text" class="form-control" name="dateFrom" id="dateFrom" placeholder="From date..." onchange="searchContent(0)">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dateTo">To date</label>
                            <input type="text" class="form-control" name="dateTo" id="dateTo" placeholder="To date..." onchange="searchContent(0)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card" id="cardContent">
			<div class="card-header">
                <h3 class="card-title">Management</h3>
				<div class="card-tools">
					<!-- <a class="btn btn-primary" href="<?=$add_new?>">Add new</a> -->
					<button class="btn btn-warning" onclick="updateStatusAll(1)">Disable</button>
                    <button class="btn btn-success" onclick="updateStatusAll(0)">Active</button>
                    <button class="btn btn-danger" onclick="$('#modal-confirm').modal()">Delete</button>
				</div>
			</div>
			<div class="card-body">
                <div>Per:
                    <select name="per_page" id="per_page" onchange="searchContent(0)">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div id="ajaxLoadContent">

                </div>
            </div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
        searchContent(0);
	});
</script>