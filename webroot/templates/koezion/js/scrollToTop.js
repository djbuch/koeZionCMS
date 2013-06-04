/*
Plugin Name: 	scrollToTop for jQuery.
Written by: 	Crivos - (http://www.crivos.com)
Version: 		0.1
*/

(function($) {
	$.extend({

		scrollToTop: function() {

			var _isScrolling = false;

			// Append Button
			$("body").append($("<a />")
							.addClass("scroll-to-top")
							.attr({
								"href": "#",
								"id": "scrollToTop"
							})
							.append(
								$("<i />")
									.addClass("icon-chevron-up icon-white")
							));
	
			$("#scrollToTop").click(function(e) {

				e.preventDefault();
				$("body, html").animate({scrollTop : 0}, 500);
				return false;

			});

			// Show/Hide Button on Window Scroll event.
			$(window).scroll(function() {

				if(!_isScrolling) {

					_isScrolling = true;

					if($(window).scrollTop() > 150) {

						$("#scrollToTop").stop(true, true).addClass("visible");
						_isScrolling = false;

					} else {

						$("#scrollToTop").stop(true, true).removeClass("visible");
						_isScrolling = false;

					}

				}

			});

		}

	});
})(jQuery);

