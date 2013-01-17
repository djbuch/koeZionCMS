$(document).ready(function() {
	
	var pricingcolumn = $('.pricing_column');
	pricingcolumn.hover(
		function() { $(this).find('.pricing_blurb').animate({top: '10px'}, 300); },
		function(){ $(this).find('.pricing_blurb').animate({top: '0px'}, 300); }
	);
	
	//Définition des marges des tables de prix
	//VOIR SI ON APPLIQUE CETTE FONCTIONNALITE
	/*
	var numberofcolumns = $('.pricing').length; //Nombre d'éléments
	if(numberofcolumns > 0) { //Si au moins 1 élément
		
		var widthofrow = $('.pricing:first').parent().width(); //On récupère la longueur de la fenêtre parente		
		var delta = Math.floor( ((widthofrow - (numberofcolumns * 148)) / numberofcolumns) / 2); //On calcule la marge
		if(delta > 0) { $('.pricing_blurb').css({marginLeft: delta, marginRight: delta}); } //On applique cette marge
	}
	*/
});