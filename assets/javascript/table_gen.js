$(document).ready(function() {
	
	var colour = "green";
	$("li .glyphicon-red").click(function() {
		colour = "red";
	});
								
	$("li .glyphicon-yellow").click(function() {
		colour = "yellow";
	});				
	
	$(".time-cell").mousedown(function(e) {
		$element = $(this);
		$element.css( 'cursor', 'pointer' );
		
		if (e.shiftKey || e.button == 2) {
		   	nukeCell("mousedown", $element);
		} else {
			paintCell("mousedown", $element, colour);
		}
	});
	
	$(".time-cell").hover( function(e) {
		$element = $(this);
		$element.css( 'cursor', 'pointer' );
		
		if (e.shiftKey || e.button == 2) {
		   	nukeCell("hover", $element);
		} else {
			paintCell("hover", $element, colour);
		}
	});
	
	$(document).mouseup(function() { mouseDown = false; });
});

$(".time-cell").on('contextmenu', function(e) {
	return false;
});

var mouseDown = false;
document.body.onmousedown = function() { 
  	mouseDown = true;
}
document.body.onmouseup = function() {
  	mouseDown = false;
}

function paintCell(mode, $element, colour) {
	nukeCell(mode, $element);
	if ( mode === "mousedown" || (mode === "hover" && mouseDown) ) {
		$element.addClass("c-" + colour);
	}
}

function nukeCell(mode, $element) {
	if ( mode === "mousedown" || (mode === "hover" && mouseDown) ) {
		$element.removeClass(function (index, css) {
    return (css.match (/(^|\s)c-\S+/g) || []).join(' ');
});
	}

}