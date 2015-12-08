$(document).ready(function() {
	
	window.current_table = "#table-half-hour";
	$(window.current_table).show();
	
	$("#hour-interval-hour").click(function(el) {
		var cellList = gatherColours(window.current_table);
		affectTable("#table-hour-on-hour", cellList, 0);
		window.current_table = "#table-hour-on-hour";
		toggleTableLink(this);
		changeSliderNotifications();
	});
	
	$("#half-hour-interval").click(function(el) {
		var cellList = gatherColours(window.current_table);
		affectTable("#table-half-hour", cellList, 1);
		window.current_table = "#table-half-hour";
		toggleTableLink(this);
		changeSliderNotifications();
	});
	
	$("#hour-interval-half-hour").click(function(el) {
		var cellList = gatherColours(window.current_table);
		affectTable("#table-hour-on-half-hour", cellList, 1);
		window.current_table = "#table-hour-on-half-hour";
		toggleTableLink(this);
		changeSliderNotifications();
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

