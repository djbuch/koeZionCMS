// Run the script on DOM ready:
$(function(){
	/*$('table.toGraph').visualize({
		type: 'line', 
		width: '800px', 
		height: '350px',
		lineWeight: 2 
	});*/
	$('table.toGraph').visualize({
		type: 'bar',
		width: '800px', 
		height: '400px',
		appendTitle: false
	});
	$('table.toGraph').css({display:'none'})
});