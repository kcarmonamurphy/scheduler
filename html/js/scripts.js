$(document).ready(function() {
	var default_values = [18, 38];
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
	  }
    });

	var ui = { values: [default_values[0], default_values[1]] }
	alterVisibleTimeSlots(ui, range);
	
	$("#half-hour-interval").click(function() {
		for (i = range.min; i < range.max; i++) {
			$( "tr#time-slot" + 2*i).show();
			$( "tr#time-slot" + 2*i+1).hide();
		}
	});
	
});

function alterVisibleTimeSlots(ui, range) {

	console.log(ui.values[0], ui.values[1], range);

	for (i = range.min; i < range.max; i++) {
		$( "tr#time-slot" + i).show();
	}
	for (i = range.min; i < ui.values[0]; i++) {
		$( "tr#time-slot" + i).hide();
	}
	for (i = range.max; i >= ui.values[1]; i--) {
		$( "tr#time-slot" + i).hide();
	}
  
}


var mouseDown = false;

$(document).ready(function() {
	
	var colour = "red";
	
	$("li.glyphicon-container-red").click(function() {
		$(".glyphicon-container").each(function() { $(this).removeClass("active") });
		$(this).addClass("active");
		colour = "red";
	});
								
	$("li.glyphicon-container-amber").click(function() {
		$(".glyphicon-container").each(function() { $(this).removeClass("active") });
		$(this).addClass("active");
		colour = "amber";
	});				
	
	$("#table").mousedown(function(e) {
		$element = $(this);
		chooseCursor(e, $element);
	});
	
	$("#table").hover(function(e) {
		$element = $(this);
		chooseCursor(e, $element);
	});
	
	$("#table").mouseup(function(e) {
		$element = $(this);
		$element.css( 'cursor', 'cell' );
	});
	
	$(".time-cell").mousedown(function(e) {
		$element = $(this);
		modifyCell(e, $element, colour);
	});
	
	$(".time-cell").hover( function(e) {
		$element = $(this);
		modifyCell(e, $element, colour);
	});
	
	$(document).mouseup(function() { 
		mouseDown = false; 
	});
});

$("#table").on('contextmenu', function(e) {
	return false;
});

//MOUSEDOWN EVENTS

document.body.onmousedown = function() {
  	mouseDown = true;

}
document.body.onmouseup = function() {
  	mouseDown = false;
}

document.body.oncontextmenu = function() {
  	mouseDown = false;
}

//FUNCTIONS

function chooseCursor(event, element) {
	if (event.shiftKey || event.button == 2) {
		element.css( 'cursor', 'url("css/eraser.png") 10 10, cell')
	} else {
		element.css( 'cursor', 'cell' );
	}
}

function modifyCell(event, element, colour) {
	if (event.shiftKey || event.button == 2) {
		nukeCell(event.type, $element);
	} else {
		paintCell(event.type, $element, colour);	
	}
}

function paintCell(mode, $element, colour) {
	nukeCell(mode, $element);
	if ( mode === "mousedown" || ((mode === "mouseenter" || mode === "mouseleave") && mouseDown) ) {
		$element.addClass("c-" + colour);
	}
}

function nukeCell(mode, $element) {
	if ( mode === "mousedown" || ((mode === "mouseenter" || mode === "mouseleave") && mouseDown) ) {
		$element.removeClass(function (index, css) {
			return (css.match (/(^|\s)c-\S+/g) || []).join(' ');
		});
	}
}