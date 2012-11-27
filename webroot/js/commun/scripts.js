$(document).ready(function() {
		
	$(".blank").attr("target", "_blank");
	
	$('.nospam').each(function(){
		var sEmail = $(this).html();
		sEmail = sEmail.replace("[arobase]","@");
		sEmail = sEmail.replace("[point]",".");
		$(this).html("<a href=\"mailto:" + sEmail + "\">" + sEmail + "</a>");
	});
	
	jQuery.extend({
		
		get_host: function() { 
	  		
			//Appel $.get_host()
	  		var sHost = window.location.host;
	  		var sHref = window.location.href;
	  		
	  		if(sHref.indexOf("/adm/", 0) > 0) { var sBack = "adm/"; } else { var sBack = ""; }	  		
	  		
	  		if(sHost == "localhost") { var sHostToReturn =  "http://" + sHost + "/www.koezion.com/" + sBack; }
	  		else { var sHostToReturn =  "http://" + sHost + "/" + sBack; }
	  			  		
	  		return sHostToReturn; 
		},
		is_back: function() { 
	  		
			//Appel $.is_back()
			var sHost = window.location.host;
	  		var sHref = window.location.href;
	  		
	  		if(sHref.indexOf("/adm/", 0) > 0) { return 1; } else { return 0; } 
		},
		get_surface_ecran: function() {
				
			//Appel $.get_surface_ecran()
			var iScreenWidth, iPageHeight;
			
			// firefox is ok
			iPageHeight = document.documentElement.scrollHeight;
			iScreenWidth = document.documentElement.scrollWidth;
			
			// now IE 7 + Opera with "min window"
			if ( document.documentElement.clientHeight > iPageHeight ) { iPageHeight  = document.documentElement.clientHeight; }
			if ( document.documentElement.clientWidth > iScreenWidth ) { iScreenWidth  = document.documentElement.clientWidth; }
			
			// last for safari
			if ( document.body.scrollHeight > iPageHeight ) { iPageHeight = document.body.scrollHeight; }
			if ( document.body.scrollWidth > iScreenWidth ) { iScreenWidth = document.body.scrollWidth; }
			
			return  {
				screenWidth: iScreenWidth, 
				screenHeight: screen.height,
				pageHeight: iPageHeight
			};
		},
		same_height: function(oElement) {
			
			//Appel $.same_height()
			var aHeights = [];
			$(oElement).each(function(){ aHeights.push( $(this).height() ); });
			var iMaxHeight = Math.max.apply(null, aHeights);
			$(oElement).css( {'height': iMaxHeight + 'px'} );
		}
	});	
});