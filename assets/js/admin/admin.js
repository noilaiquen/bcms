function showLoading(cardContent = 'cardContent') {
	$('#'+cardContent).append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
}

function hideLoading(cardContent = 'cardContent') {
	var item = document.getElementsByClassName('overlay');
	document.getElementById(cardContent).removeChild(item[0]);
}

function showSuccess(message) {
    dismissAlert()
	var html = '<div id="alertSuccess">';
	html += '<div class="alert alert-success alert-dismissible">';
	html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
	html += '<h5><i class="icon fa fa-check"></i> Alert!</h5>';
	html += message;
	html += '</div>';
	html += '</div>';
	document.getElementById('alertArea').innerHTML = html;
}

function showError(message) {
    dismissAlert()
	var html = '<div id="alerDanger">';
	html += '<div class="alert alert-danger alert-dismissible">';
	html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
	html += '<h5><i class="icon fa fa-ban"></i> Alert!</h5>';
	if (typeof message == 'object') {
		html += '<ul>';
		message.forEach(function (error) {
			html += '<li>' + error + '</li>';
		});
		html += '</ul>';
	} else {
		html += message;
	}
	html += '</div>';
	html += '</div>';
	document.getElementById('alertArea').innerHTML = html;
}

function dismissAlert() {
	var alertSuccess = document.getElementById('alertSuccess');
	var alerDanger = document.getElementById('alerDanger');

	if (alertSuccess != null) {
		document.getElementById('alertArea').innerHTML = '';
	}
	if (alerDanger != null) {
		document.getElementById('alertArea').innerHTML = '';
	}
}

function searchContent(start, per_page) {
	if (per_page == undefined) {
		if ($('#per_page').val()) {
			per_page = $('#per_page').val();
		} else {
			per_page = 10;
		}
	}

	if (start == undefined) {
		start = 0;
	}
	// $('#start').val(start);

	var func_sort = $('#func_sort').val();
	var type_sort = $('#type_sort').val();
	
	$.ajax({
		url: route_url + '/ajax_loadContent',
		type: 'POST',
		dataType: 'html',
		data: {
			func_sort: func_sort,
			type_sort: type_sort,
			start: start,
			per_page: per_page,
			dateFrom: $('#dateFrom').val(),
			dateTo: $('#dateTo').val(),
			search_content: $('#search_content').val(),
			filter1: $('#filter1').val(),
			filter2: $('#filter2').val(),
		},
		beforeSend: function () {
			// dismissAlert();
			showLoading();
		},
		complete: function () {
			hideLoading();
			if (type_sort == 'DESC') {
				$('#' + func_sort).removeClass('sorting');
				$('#' + func_sort).addClass('sorting_desc');
			} else {
				$('#' + func_sort).removeClass('sorting');
				$('#' + func_sort).addClass('sorting_asc');
			}
		},
		success: function (html) {
			$('#ajaxLoadContent').html(html);
		}
	});
}

function enterSearch(e) {
	if (e.keyCode == 13) {
		searchContent(0);
	}
}

function selectItem(id) {
	var itemCheck = document.getElementById('item' + id);
	if (itemCheck.checked == true) {
		document.getElementsByClassName('item_row' + id)[0].classList.add('row_active');
	} else {
		document.getElementsByClassName('item_row' + id)[0].classList.remove('row_active');
	}
}

function selectAllItems(max) {
	if (document.getElementById('selectAllItems').checked == true) {
		for (var i = 0; i < max; i++) {
			if (document.getElementById('item' + i) != null) {
				document.getElementsByClassName('item_row' + i)[0].classList.add('row_active');
				itemCheck = document.getElementById('item' + i);
				itemCheck.checked = true;
			}
		}
	} else {
		for (var i = 0; i < max; i++) {
			if (document.getElementById('item' + i) != null) {
				document.getElementsByClassName('item_row' + i)[0].classList.remove('row_active');
				itemCheck = document.getElementById('item' + i);
				itemCheck.checked = false;
			}
		}
	}
}

function sort(func) {
	var func_sort = document.getElementById('func_sort');
	var type_sort = document.getElementById('type_sort');
	var per_page = document.getElementById('per_page');
	
	if (func == func_sort.value) {
		type_sort.value = type_sort.value == 'DESC' ? 'ASC' : 'DESC';
	} else {
		func_sort.value = func;
		type_sort.value = 'DESC';
	}
	searchContent(0, per_page.value);
}

function deleteAll() {
    var max = document.getElementById('per_page').value;
    var ids = [];
    for (var i = 0; i < max; i++) {
        var item = document.getElementById('item' + i);
		if (item!=null && item.checked==true) {
            ids.push(item.value);
		}
    }
    
	if (ids.length > 0) {
		$.ajax({
			url: route_url + '/delete',
			type: 'post',
			dataType: 'JSON',
			data: {
				ids: ids
			},
			beforeSend: function () {
				dismissAlert();
			},
			success: function (json) {
				if (json.status == 1) {
					searchContent($('#start').val(), $('#per_page').val());
					showSuccess(json.message);
				} else {
					showError(json.message);
				}
			}
		});
	}
	$('#modal-confirm').modal('hide');
		
}

function updateStatus(id, status) {
    $.post(route_url + '/updateStatus', {
        id: id,
        status: status
	}, function (res) {
		document.getElementById('updateStatus' + id).innerHTML = res;
    });
}

function updateStatusAll(status) {
    var max = document.getElementById('per_page').value;
    for (var i = 0; i < max; i++) {
        var item = document.getElementById('item' + i);
		if (item!=null && item.checked==true) {
            updateStatus(item.value, status);
		}
    }
}
