(function($){
    "use strict"; // Start of use strict
    
    $(document).ready(function(){
        
    	init_menu();
		init_map();
		init_masonry();
	});
    
})(jQuery); // End of use strict

/**********************/
/*    MENU GENERAL    */
/**********************/
function init_menu() {
	(function($) {
		
		$('.nav.navbar-nav').smartmenus({
			'subIndicators': false,
			'subMenusSubOffsetX': 5,
			'subMenusSubOffsetY': 15			
		});
		
	})(jQuery);
}

/********************/
/*    GOOGLE MAP    */
/********************/
function init_map() {
	(function($) {
		
		var gmMapDiv = $("#contact_map_element .map");
		
		if(gmMapDiv.length) {
			
			var gmMarkerAddress = gmMapDiv.attr("data-address");
			var gmMarkerLat 	= gmMapDiv.attr("data-lat");
			var gmMarkerLng 	= gmMapDiv.attr("data-lng");

			gmMapDiv
				.gmap3({
        			center:[gmMarkerLat, gmMarkerLng],
        			zoom:14
      			})
      			.marker([
        			{address:gmMarkerAddress, icon: $('body').getdata('template') + "img/map-marker.png"}
      			]);
        }
	})(jQuery);
}

/*****************/
/*    MASONRY    */
/*****************/
function init_masonry(){
    (function($) {    
    
    	var masonryElement = $('.masonry');
    	
    	if(masonryElement.length) {
    	
			var $masonry = masonryElement.imagesLoaded( function() {
				// init Masonry after all images have loaded
				$masonry.masonry({
					itemSelector: '.masonry_item'
				});
			});        
    	}
    })(jQuery);
}