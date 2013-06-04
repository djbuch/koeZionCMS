//$(document).ready(function() {
		
	/* Hack for all browsers to load the slider nicier way. The #slider has default property of display:none in css(for nice IE loading), then when js is loaded it changes to block (so Opera can
	render height of the slider properly while loading images), and then hides it again. Later, when all images are loaded - the fadeIn function kicks in. */
	//$('#slider').css({display: "block"}).hide();
//});
/* Start of functions initialized after full load of page */
//$(window).load(function(){
	
	/* Load the slider nicely with fade-in effect and wait till all images are loaded */
	//$('#slider').fadeIn(900);
	//$('.inner_main .loader').css({display: "none"});
//});
$(function() {
	
	var Page = (function() {

		var $navArrows = $('#nav-arrows').hide(),
		$navDots = $('#nav-dots').hide(),
		$nav = $navDots.children( 'span' ),
		$shadow = $('#shadow').hide(),
		slicebox = $('#sb-slider').slicebox( {
			onReady : function() {

				$navArrows.show();
				$navDots.show();
				$shadow.show();

			},
			onBeforeChange : function( pos ) {

				$nav.removeClass('nav-dot-current');
				$nav.eq(pos).addClass('nav-dot-current');	
			},
		orientation : 'r',
		cuboidsRandom : true,
		disperseFactor : 30,
		autoplay : true,
		interval : 6500
		} ),
		
		init = function() { initEvents(); },
		initEvents = function() {

			// add navigation events
			$navArrows.children(':first').on('click', function() {

				slicebox.next();
				return false;	
			} );

			$navArrows.children(':last').on('click', function() {
				
				slicebox.previous();
				return false;	
			} );

			$nav.each(function(i) {
			
				$(this).on( 'click', function( event ) {
					
					var $dot = $( this );
					
					if(!slicebox.isActive()) {

						$nav.removeClass('nav-dot-current');
						$dot.addClass('nav-dot-current');								
					}
					
					slicebox.jump(i + 1);
					return false;							
				});
				
			} );					
			
		
			$('.slicebox').hover(
				function() { slicebox.pause(); }, 
				function() { slicebox.play(); }
			);

		};

		return { init : init };

	})();

	Page.init();

});