(function($){
    "use strict"; // Start of use strict
    
    $(document).ready(function(){
        
		init_bg_page('.page_header_element .illustration img', '.page_header_element');
		init_bg_page('.page_footer_element .illustration img', '.page_footer_element');
	});
    
})(jQuery); // End of use strict

function init_bg_page(imgElement, bgElement){
    (function($) {    
    
    	var img = $(imgElement);
    	if(img.length) {

    		$(bgElement)
    			.addClass('with_background')
    			.css({
    				'background-image': 'url("' + img.attr('src') + '")' 
    			});
    	}
    })(jQuery);
}