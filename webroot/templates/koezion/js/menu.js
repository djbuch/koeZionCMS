$(document).ready(function() {
	
	$('#menu > ul > li').prepend('<span class="menu_box_left"></span>').append('<span class="menu_box_right"></span>');
	
	/* Menu jQuery rollout, additional styling allowing for rounded borders */
	var Menus = $('#menu ul li');
	
	if(!($.browser.msie && $.browser.version.substr(0,1)<8)){Menus.find('ul li').has('ul li').find('>a').append('<span> &raquo;</span>');}
	
	Menus.find('ul li:first-child').addClass('first_sub png_bg');
	
	Menus.find('ul').append('<li class="last_sub png_bg"></li>');
	
	Menus.find('ul').css({display: "none"}); // Opera Fix
	
	Menus.hover(
		function(){
			
			$(this).not(':has(ul)').find('> a').addClass('menu_box_mid_bg');
			$(this).has('ul').find('> a').addClass('menu_box_mid_bg_has_li');
			$('span.menu_box_left', this).addClass('menu_box_left_bg');
			$('span.menu_box_right', this).addClass('menu_box_right_bg');
		},
		function() {
		
			$(this).not(':has(ul)').find('> a').removeClass('menu_box_mid_bg');
			$(this).has('ul').find('> a').removeClass('menu_box_mid_bg_has_li');
			$('span.menu_box_left', this).removeClass('menu_box_left_bg');
			$('span.menu_box_right', this).removeClass('menu_box_right_bg');
		}
	);
	
	Menus.hoverIntent({
		over: makeTall, 
		timeout: 300, 
		out: makeShort
	});
	
	function makeTall(){ $(this).find('ul:first').slideDown({queue:false,duration:220});}
	
	function makeShort(){ $(this).find('ul:first').fadeOut({queue:true,duration:200});}
});