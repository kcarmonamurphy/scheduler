function submit() {
	console.log("submit");
	
	var meeting_name = $("#meeting-name input").val();
	var emails = new Array();
	$("[id^=email]").each(function(index, el) {
		if ($(this).parent(".form-group").hasClass("has-success"))
			emails[index] = $(this).val();
	});
	
	var grid = new Array();
	var days = $(window.current_table + " .day:visible").size();
	for (i = 0; i < days; i++) {
		grid[i] = new Array();
	}
	$(window.current_table + " .c-red:visible").each(function() {
		grid[$(this).data("day")][$(this).data("time-slot")] = "red";
	});
	$(window.current_table + " .c-amber:visible").each(function() {
		grid[$(this).data("day")][$(this).data("time-slot")] = "amber";
	});
	
	var top_range = $('#slider-range').slider("values", 0);
	var bottom_range = $('#slider-range').slider("values", 1);
	
	var owner = $("#meeting-owner input").val();

	var object = {
		grid: grid,
		days: days,
		top_range: top_range,
		bottom_range: bottom_range,
		meeting_name: meeting_name,
		owner: owner,
		emails: emails,
		current_table: window.current_table,
	}
	
	$.ajax({
		type: "POST",
		url: "/newmeeting",
		success: function(data) {
		  $("#sendToInviteesModal .modal-body").html(data);
		  eventId = data['eventId'];
		  document.location.href = ("http://kevcom.ca/"+eventId);
		},
		error: function(err) {
			$("#sendToInviteesModal .modal-body").html(err);
		},
 		data: object,
	});
}