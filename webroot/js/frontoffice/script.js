$(document).ready(function() {
		
	/* Automated sidebar top/bottom finishing graphics */
	if(!($.browser.msie && $.browser.version=="6.0")) {
		
		$('.sidebar')
			.wrapInner('<div class="sidebar"></div>')
			.prepend('<div class="top_sidebar_mask"></div>')
			.append('<div class="bottom_sidebar_mask"></div>')
			.removeClass('sidebar');
		
		$('.sidebar_mirror')
			.wrapInner('<div class="sidebar_mirror"></div>')
			.prepend('<div class="top_sidebar_mask_mirror"></div>')
			.append('<div class="bottom_sidebar_mask_mirror"></div>')
			.removeClass('sidebar_mirror');
	};
	
	/* Inner HR autofill */
	$('.inner_main .hr').append('<div class="inner_hr"></div>');
		
	/* Hack for all browsers to load the slider nicier way. The #slider has default property of display:none in css(for nice IE loading), then when js is loaded it changes to block (so Opera can
	render height of the slider properly while loading images), and then hides it again. Later, when all images are loaded - the fadeIn function kicks in. */
	$('#slider').css({display: "block"}).hide();
	
	/* Prepare border magic image loading */
	$('.inner_main .border_magic, #footer .border_magic').css({display: "block"}).hide();
		
	/* Toggler */
	$('div.toggler:not(.open)').hide();
	$('.toggle').click(function() { $(this).toggleClass("active").next().slideToggle("fast"); });
	
	/*Position du bouton des focus*/
	$('.focus').focusWidth('.gs_3');	
	
	$('.focus').sameHeights('.gs_3');	
	$('.focus .gs_3').css({'position': 'relative'});
	$('.focus .gs_3 .superbutton').css({'position': 'absolute', 'bottom': 0});	
});

/* Start of functions initialized after full load of page */
$(window).load(function(){
	
	/* Load the slider nicely with fade-in effect and wait till all images are loaded */
	$('#slider').fadeIn(900);
	$('.inner_main .loader').css({display: "none"});
	
	/* Innitialize Nivo Slider */
	$('#slider').nivoSlider({		
		directionNav:false,
		captionOpacity:0.85,
		slices:10,
		pauseTime:8000,
		keyboardNav:true,
		pauseOnHover:true
	});

	/* Add special rounded corners to the Slider */
	$('.inner_main .nivoSlider').append('<div class="slider_cover_tl png_bg"></div><div class="slider_cover_tr png_bg"></div><div class="slider_cover_br png_bg"></div><div class="slider_cover_bl png_bg"></div>');
	
	/* Make the Slider navigation bullets align to center automaticly */
	var dotsMargin = $('.inner_main .nivo-controlNav').width();
	$('.inner_main .nivo-controlNav').css('margin-left', -dotsMargin/2);
	
	/* Adding some Border magic */
	var borderSubject = $('.inner_main .border_magic, #footer .border_magic');
	borderSubject.wrap('<div class="add_border" />');
	
	/* Transfering alignment to added border */
	var bordered = $('.inner_main .add_border, #footer .add_border');
	bordered.find('.alignleft').removeClass('alignleft').parent().addClass('alignleft');
	bordered.find('.alignright').removeClass('alignright').parent().addClass('alignright');
	bordered.find('.no_bottom_margin').removeClass('no_bottom_margin').parent().addClass('no_bottom_margin');
	
	
	/* Making the added border width completely automatic */
	borderSubject.each(function(){
	
		var addBordi = $(this).width();
		$(this).parent().width(addBordi+10);
	});
	
	/* Smooth image load */
	//borderSubject.fadeIn(1500).parent().delay(1500).queue(function() {$(this).css('background-image','url(css/img/zoom.png)');});
	borderSubject.fadeIn(1500).parent().delay(1500).queue(function() {$(this).addClass('img_zoom');});	
	
	/* Catch the height of longest footer column and stretch the others to the same size */
	var highHeels = 0;
	$('#footer .widget').each(function(){
	
		var topHeels = $(this).height();
		if(topHeels > highHeels){highHeels = topHeels};
	});
	$('#footer .widget').height(highHeels);
	
	/* Cycle the "Hot stuff this week" */
	/*$('.hotstuff ul, ul.testimonials').cycle({
		fx: 'fade',
		timeout:       4000,
		speed:         1000,
		before: onAfter
	});*/
	function onAfter(curr, next, opts, fwd){
        //get the height of the current slide
        var ht = $(this).height();
        //set the container's height to that of the current slide
        $(this).parent().animate({height: ht});
	}
	
	$('a').filter(function() { return this.hostname && this.hostname !== location.hostname; }).attr("target", "_blank");
	$(".blank").attr("target", "_blank");
});