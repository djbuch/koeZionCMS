$(document).ready(function() {
		
	/* Hack for all browsers to load the slider nicier way. The #slider has default property of display:none in css(for nice IE loading), then when js is loaded it changes to block (so Opera can
	render height of the slider properly while loading images), and then hides it again. Later, when all images are loaded - the fadeIn function kicks in. */
	$('#slider').css({display: "block"}).hide();
});

/* Start of functions initialized after full load of page */
$(window).load(function(){
	
	/* Load the slider nicely with fade-in effect and wait till all images are loaded */
	$('#slider').fadeIn(900);
	$('.inner_main .loader').css({display: "none"});
	
	/* Innitialize Nivo Slider */
	$('#slider').nivoSlider({directionNav:false});

	/* Add special rounded corners to the Slider */
	//$('.inner_main .nivoSlider').append('<div class="slider_cover_tl png_bg"></div><div class="slider_cover_tr png_bg"></div><div class="slider_cover_br png_bg"></div><div class="slider_cover_bl png_bg"></div>');
});