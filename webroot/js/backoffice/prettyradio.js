$(document).ready(function() {
	// Sean Foushee: added the following lines to convert the checkboxes to pretty checkboxes by:
	// 1. adding the checklist class to the block element (div) that contains all checkboxes and...
	// 2. dynamically add the anchor tags to the block elements (p) containing each individual checkbox
	
	$("div.prettyCheckboxes").livequery(function() { 
		$(this).addClass("checklist");
	});
	
	$("div.checklist p").livequery(function() { 
	$(this).append('<a class="checkbox-select" href="#">Select</a><a class="checkbox-deselect" href="#">Cancel</a>');
	});
	
	// code from here on is the same as Aaron Weyenberg
	$(".checklist .checkbox-select").livequery(function() { 
	$(this).click(
		function(event) {
			event.preventDefault();
			$(this).parent().addClass("selected");
			$(this).parent().find(":checkbox").attr("checked","checked");
		}
	);
	});
	
	$(".checklist .checkbox-deselect").livequery(function() { 
	$(this).click(
		function(event) {
			event.preventDefault();
			$(this).parent().removeClass("selected");
			$(this).parent().find(":checkbox").removeAttr("checked");
		}
	);
	});
	
	// Pretty Radio Buttons, by Sean Foushee based on Pretty Checkboxes by Aaron Weyenberg
	$("div.prettyRadiobuttons").livequery(function() { 
	$(this).addClass("radiolist");
	});
	
	$("div.radiolist p").livequery(function() { 
	$(this).append('<a class="radio-select" href="#">Select</a><a class="radio-deselect" href="#">Annuler</a>');
	});
	
	// code from here on is the same as Aaron Weyenberg
	$(".radiolist .radio-select").livequery(function() { 
	$(this).click(
		function(event) {
			event.preventDefault();
			var $boxes = $(this).parent().parent().children();
			$boxes.removeClass("selected");
			$(this).parent().addClass("selected");
			$(this).parent().find(":radio").attr("checked","checked");
		}
	);
	});
	
	$(".radiolist .radio-deselect").livequery(function() { 
	$(this).click(
		function(event) {
			event.preventDefault();
			$(this).parent().removeClass("selected");
			$(this).parent().find(":radio").removeAttr("checked");
		}
	);
	});
	
	// Almost Pretty Checkboxes
	$("div.prettyCheckboxesAlmost").livequery(function() { 
	$(this).addClass("checklistalmost");
	});
	
	// Almost Pretty Radio Buttons
	$("div.prettyRadiobuttonsAlmost").livequery(function() { 
	$(this).addClass("radiolistalmost");
	});
});