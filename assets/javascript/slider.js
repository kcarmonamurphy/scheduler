$(document).ready(function() {
	var default_values = [19, 39];
	var range = { min: 0, max: 48 }
	
	$( "#slider-range" ).slider({
      orientation: "horizontal",
      range: true,
     	min: range.min,
		max: range.max,
		step: 1,
		values: default_values,
      slide: function(event, ui) {
		  alterVisibleTimeSlots(ui, range);
		  changeSliderNotifications(ui);
	  }
    });

	var ui = { values: [default_values[0], default_values[1]] }
	alterVisibleTimeSlots(ui, range);
	changeSliderNotifications();
	
	$("#half-hour-interval").click(function() {
		for (i = range.min; i < range.max; i++) {
			$( "tr#time-slot" + 2*i).show();
			$( "tr#time-slot" + 2*i+1).hide();
		}
	});
	
});

function changeSliderNotifications() {
	$(".range-left").html($("tr.time-slot-row:visible").first().find(".time-slot").html());
	$(".range-right").html($("tr.time-slot-row:visible").last().next().find(".time-slot").html());
}

function alterVisibleTimeSlots(ui, range) {

	console.log(ui.values[0], ui.values[1], range);

	for (i = range.min; i < range.max; i++) {
		$( "tr.time-slot" + i).show();
	}
	for (i = range.min; i < ui.values[0]; i++) {
		$( "tr.time-slot" + i).hide();
	}
	for (i = range.max; i >= ui.values[1]; i--) {
		$( "tr.time-slot" + i).hide();
	}
  
}

