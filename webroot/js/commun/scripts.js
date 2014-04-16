//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REDEFINITION DE LA FONCTION DATA QUI NE FONCTIONNE PAS CORRECTEMENT PAR MOMENT SURTOUT POUR LE SET    //	
(function($){

	$.fn.getdata = function(attr){
		
		var dataAttr = 'data-' + attr;
    	return $(this).attr(dataAttr);
	};	
	
	$.fn.setdata = function(attr, value){
		
		var dataAttr = 'data-' + attr;
    	return $(this).attr(dataAttr, value);
	};
	
})(jQuery);
	
$(document).ready(function() {
		
	$('a').filter(function() { return this.hostname && this.hostname !== location.hostname; }).attr("target", "_blank");
	$(".blank").attr("target", "_blank");
	
	$('.nospam').each(function(){
		var sEmail = $(this).html();
		sEmail = sEmail.replace("[arobase]","@");
		sEmail = sEmail.replace("[point]",".");
		$(this).html("<a href=\"mailto:" + sEmail + "\">" + sEmail + "</a>");
	});
	
	var baseurl = $('body').getdata('baseurl');
	
	jQuery.extend({
		
		get_host: function() {

			//Appel $.get_host()
	  		var sHost = window.location.host; //Récupération du host
	  		var sHref = window.location.href; //Récupération de l'url
	  		
	  		sHrefExplode = sHref.substr(7, sHref.length); //Mise en tableau de l'url en ayant supprimé http://
	  		sHrefExplode = sHrefExplode.split('/');

	  		if(sHref.indexOf("/adm/", 0) > 0) { var sBack = "adm/"; } else { var sBack = ""; }	  		
	  		
	  		if(sHost == "localhost") { var sHostToReturn =  "http://" + sHost + baseurl + "/" + sBack; }
	  		else { var sHostToReturn =  "http://" + sHost + "/" + sBack; }
	  			  		
			var sHostToReturn =  "http://" + sHost + baseurl + "/" + sBack;
	  		return sHostToReturn;
			
			/*RAJOUT DU 17/06/2013 A TESTER POUR VOIR SI CA FONCTIONNE CORRECTEMENT*/
	  		/*SUppression le 21/06/2013 car ça fonctionnait mal*/
			/*http://stackoverflow.com/questions/6179882/getbaseurl-javascript-call-function-in-a-href*/
		/*	var url = location.href;  // entire url including querystring - also: window.location.href;
			var baseURL = url.substring(0, url.indexOf('/', 14));

			if (baseURL.indexOf('http://localhost') != -1) {
			    // Base Url for localhost
			    var url = location.href;  // window.location.href;
			    var pathname = location.pathname;  // window.location.pathname;
			    var index1 = url.indexOf(pathname);
			    var index2 = url.indexOf("/", index1 + 1);
			    var baseLocalUrl = url.substr(0, index2);
			
			    return baseLocalUrl + "/";
			}
			else {
			    // Root Url for domain name
			    return baseURL + "/";
			}*/
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
				pageHeight: iPageHeight,
				windowHeight: $(window).height(),
				windowWidth: $(window).width()	
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
Array.prototype.unset = function(val){
    var index = this.indexOf(val)
    if(index > -1) { this.splice(index,1); }
}
function log(text) { console.log(text); }