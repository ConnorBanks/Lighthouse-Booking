$(function() {
	$('.grid-stack-default').gridstack({
		resizable: {
			handles: 'e, w'
		},
		acceptWidgets: '.grid-stack-single-item',
		height: 1
	});
	$('.grid-stack-default').on('gsresizestop', function(event, elem) {
		var el_x = elem.dataset.gsX;
		var el_width = elem.dataset.gsWidth;
//console.log(el_x, el_width, el_time, $(elem));
		run_ajax(el_x, el_width, $(elem));
	}).on('dragstop', function(event, ui) {
		setTimeout(function() {
			var el_x = event.target.dataset.gsX;
			var el_width = event.target.dataset.gsWidth;
			console.log(el_x, el_width, $(ui), $(event.target));
			run_ajax(el_x, el_width, $(event.target));
		}, 125);
	}).on('added', function(event, items) {
		console.log($(items));
		var item = items[0];
		setTimeout(function() {
			var el_x = item.el[0].dataset.gsX;
			var el_y = item.el[0].dataset.gsY;
			var el_width = item.el[0].dataset.gsWidth;
			console.log(el_x, el_width, $(item.el), $(event));
			if (el_y == 0) {
				run_ajax(el_x, el_width, $(item.el));
			} else {
				alert('Unable to place booking, please try again');
				window.location = 'adminSchedule.php';
			}
		}, 125);
	});
	$('#grid-stack-unallocated').gridstack({
		acceptWidgets: '.grid-stack-single-item',
		width: 10,
		disableResize: true,
		height: 1
	});
	$('#grid-stack-unallocated').on('added', function(event, items) {
		console.log($(items));
		$('#nobookings').remove();
		var item = items[0];
		setTimeout(function() {
			var el_x = item.el[0].dataset.gsX;
			var el_y = item.el[0].dataset.gsY;
			var el_width = item.el[0].dataset.gsWidth;
			console.log(el_x, el_width, $(item.el), $(event));
			run_ajax(el_x, el_width, $(item.el));
		}, 125);
	});
});

function run_ajax(el_x, el_width, el) {
	console.log(el_x, el_width, el);
	var table_id = el.parent().data('table_id');
	var booking_id = el.data('booking_id');
	//$(el).children('div').children('span').html(table_id + ' - ' + booking_id + ' - ' + el_x + ' - ' + el_width);
	if (!booking_id) {
		alert('No ID in data, please try again');
	} else {
		$.ajax({
				url: 'ajax.php?request=schedule',
				type: 'POST',
				data: {
					el_x: el_x,
					el_width: el_width,
					table_id: table_id,
					booking_id: booking_id
				},
			})
			.done(function() {
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			});
	}
}

/*function validate_el_pos(grid, el_data) {
	if (grid.willItFit(el_data.gsX, el_data.gsY, el_data.gsWidth, el_data.gsHeight, true)) {
		return true;
	} else {
		alert('Not enough free space to place the booking');
		return false;
	}
}*/