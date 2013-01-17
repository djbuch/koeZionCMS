$(document).ready(function() {
	
	$('.zoomer img').hover(
		function() { $(this).animate({"opacity": "0.45"},{queue:true,duration:300}); }, 
		function() { $(this).animate({"opacity": "1"},{queue:true,duration:300}); }
	);
	$("a[rel^='prettyPhoto']").prettyPhoto({social_tools: ''});	
});