$(document).ready(function() {
	
	window.current_table = "#table-half-hour";
	$(window.current_table).show();
	
	$("#hour-interval-hour").click(function(el) {
		var cellList = gatherColours(window.current_table);
		affectTable("#table-hour-on-hour", cellList, 0);
		window.current_table = "#table-hour-on-hour";
		toggleTableLink(this);
	});
	
	$("#half-hour-interval").click(function(el) {
		var cellList = gatherColours(window.current_table);
		affectTable("#table-half-hour", cellList, 1);
		window.current_table = "#table-half-hour";
		toggleTableLink(this);
	});
	
	$("#hour-interval-half-hour").click(function(el) {
		var cellList = gatherColours(window.current_table);
		affectTable("#table-hour-on-half-hour", cellList, 1);
		window.current_table = "#table-hour-on-half-hour";
		toggleTableLink(this);
	});
	
	$("#show-hide-weekend").click(function(el) {
		$(this).toggleClass("active");
		$('table tr').find('td:eq(6),th:eq(6),td:eq(7),th:eq(7)').toggle("300");
	});
});

function toggleTableLink(el) {
	$(".dropdown-menu li[role='separator']").first().prevAll().each(function(el) {
		$(this).removeClass("active");
	})
	$(el).toggleClass("active");
}

function addColour(element, colour) {
	if (colour == "red") {
		$(element).addClass("c-red");
		$(element).removeClass("c-amber");
	} else {
		$(element).addClass("c-amber");
		$(element).removeClass("c-red");
	}
}

function removeColour(element) {
	$(element).removeClass("c-amber");
	$(element).removeClass("c-red");
}

function affectTable(tableId, cellList, shift) {
	var cells = $(tableId + " .time-cell");
	$(cells).each(function(index, element) {
		var ts = $(element).data("time-slot");
		var day = $(element).data("day");		  
		
		switch (cellList[day][ts-shift]) {
			case "red": 
				addColour(element, "red");
				break;
			case "amber": 
				if (cellList[day][ts+1-shift] == "red") {
					addColour(element, "red");
				} else {
					addColour(element, "amber");
				}
				break;
			case undefined:
				if (cellList[day][ts+1-shift]) {
					addColour(element, cellList[day][ts+1-shift]);
				} else {
					removeColour(element);
				}
				break;
		}
	});
	$(tableId).show();
}

function gatherColours(tableId) {
	$(tableId).hide();
	var $redCells = $(tableId + " .c-red");
	var $amberCells = $(tableId + " .c-amber");
	var cellList = [];
	for (i = 0; i < 7; i++) {
		cellList[i] = [];
	}
	$amberCells.each(function(index, element) {
		var ts = $(element).data("time-slot");
		var day = $(element).data("day");
		cellList[day][ts] = "amber";
	});
	$redCells.each(function(index, element) {
		var day = $(element).data("day");
		var ts = $(element).data("time-slot");
		cellList[day][ts] = "red";
	});
	return cellList;
}


$(document).ready(function() {
	$("#sendToInvitees").click(function() {
		var value = $.trim($("#meeting-name input").val());
		if (value.length == 0) {
			$("#meeting-name input").focus();
			$("#meeting-name").addClass("has-error");
			$("#meeting-name-error").removeClass("hidden");
			return false;
		} else {
			$("#meeting-name").removeClass("has-error");
			$("#meeting-name-error").addClass("hidden");
		}				 
	});
	
	$("#meeting-name input").keyup(function(el) {
		if (($.trim($(this).val()).length)) {
			$("#meeting-name").addClass("has-success");
		} else {
			$("#meeting-name").removeClass("has-success");
		}			
	});
	
	$("input[type='email']").each(function() {
		$(this).change(function(el) {
			validateEmail(this);
		});
		$(this).keyup(function(el) {
			validateEmail(this);
		});
	})

	$("#sendInviteesSubmit").click(function() {
		var email0Success = $("#email0").parent(".form-group").hasClass("has-success");
		var totalEmailSuccess = 0;
		$("[id^=email]").each(function() {
			if ($(this).parent(".form-group").hasClass("has-success"))
				totalEmailSuccess++;
		});
		
		if (email0Success && totalEmailSuccess > 1) {
			console.log("submit");
			$("#email-error").addClass("hidden");
		} else {
			$("#email-error").removeClass("hidden");
			return false;
		}

	});
});

function validateEmail(el) {
	if (($.trim($(el).val()) == '')) {
		$(el).parent(".form-group").removeClass("has-error");
		$(el).parent(".form-group").removeClass("has-success");
	}
	else if (isEmail($(el).val())) {
		$(el).parent(".form-group").removeClass("has-error");
		$(el).parent(".form-group").addClass("has-success");
	}
	else {
		$(el).parent(".form-group").addClass("has-error");
	}
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
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
		$( "tr.time-slot" + i).show();
	}
	for (i = range.min; i < ui.values[0]; i++) {
		$( "tr.time-slot" + i).hide();
	}
	for (i = range.max; i >= ui.values[1]; i--) {
		$( "tr.time-slot" + i).hide();
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
	
	$("table").mousedown(function(e) {
		$element = $(this);
		chooseCursor(e, $element);
	});
	
	$("table").hover(function(e) {
		$element = $(this);
		chooseCursor(e, $element);
	});
	
	$("table").mouseup(function(e) {
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

$("table").on('contextmenu', function(e) {
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
		$element.addClass("modified");
	}
}

function nukeCell(mode, $element) {
	if ( mode === "mousedown" || ((mode === "mouseenter" || mode === "mouseleave") && mouseDown) ) {
		$element.removeClass(function (index, css) {
			return (css.match (/(^|\s)c-\S+/g) || []).join(' ');
		});
	}
}