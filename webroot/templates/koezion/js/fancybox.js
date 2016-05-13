$(document).ready(function() {
	$(".fancybox").fancybox({
		afterLoad: function() {
			
			var link 		= $(this.element).data('link'); 
			var linkText 	= $(this.element).data('linktext'); 
			if(link) { 
				
				this.title = this.title + '<br /><a href="' + link + '">' + linkText + '</a> ';
			}
		},
		helpers : {
			title: {
				type: 'inside'
			}
		}
	});
});