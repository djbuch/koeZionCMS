$(document).ready(function(){ 	
	
	//prettyPrint();
	
	// ALERTS SUR LES BOUTONS DE SUPPRESSION EN MODE LISTE
	$(".deleteBox").each(function() { //Pour chaque éléments de la classe deleteBox
	
		var url = $(this).attr('href'); //On récupère l'url
		
		//On supprime les attributs href et onclick on rajoute un attribut action et on change le css
		$(this)
			.removeAttr('href')
			.removeAttr('onclick')
			.attr('action', url)
			.css('cursor', 'pointer');  
	});		
	
	$(".deleteBox").click( function() { //Lors du clic sur un bouton suppression
		
		var current = $(this); //Récupération de l'élément courant
		jConfirm('Voulez vous supprimer?', 'Attention', function(r) { //On lance une box de confirmation
			
			if(r) { $(location).attr('href', current.attr('action')); } //Si vrai on exécute l'url
		});
	});	
	
	$(".emailingBox").each(function() { //Pour chaque éléments de la classe deleteBox
	
		var url = $(this).attr('href'); //On récupère l'url
		
		//On supprime les attributs href et onclick on rajoute un attribut action et on change le css
		$(this)
			.removeAttr('href')
			.removeAttr('onclick')
			.attr('action', url)
			.css('cursor', 'pointer');  
	});		
	
	$(".emailingBox").click( function() { //Lors du clic sur un bouton suppression
		
		var current = $(this); //Récupération de l'élément courant
		jConfirm('Voulez vous vraiment envoyer ce mailing?', 'Attention', function(r) { //On lance une box de confirmation
			
			if(r) { $(location).attr('href', current.attr('action')); } //Si vrai on exécute l'url
		});
	});	
	
	//ALERTS SUR LES FORMULAIRES DE SUPPRESSION EN MODE LISTE
	$(".deleteFormBox").each(function() { //Pour chaque boutons ayant la classe deleteFormBox 
		
		var formId = $(this).attr('href'); //On récupère l'identifiant du formulaire à soumettre
		
		//On supprime les attributs href et onclick on rajoute un attribut formId et on change le css
		$(this)
			.removeAttr('href')
			.removeAttr('onclick')
			.attr('formId', formId)
			.css('cursor', 'pointer');
	});	
	
	$(".deleteFormBox").click( function() { //Lors du clic sur le bouton de suppression
		
		var current = $(this); //Récupération de l'élément courant
		jConfirm('Voulez vous supprimer?', 'Attention', function(r) { //On lance une box de confirmation
			
			if(r) { //Si vrai on submit le formulaire
				
				var formId = current.attr('formId');
				$('#' + formId).submit();
			}
		});
	});
		
	//Gestion du tri sur les tableaux
	$(".list_elements").sortable({
		items: '.sortable', 
		revert: true, 
		axis: 'y', 
		cursor: 'move',
		update: function() {  // callback quand l'ordre de la liste est changé
			
			var sHref = window.location.href;			
			var iSubStringPosition = sHref.indexOf('index.html?displayall=1', 0);
			var sUrl = sHref.substr(0, iSubStringPosition) + 'ajax_order_by.html';			
			var order = $(this).sortable('serialize'); // récupération des données à envoyer			
			$.post(sUrl, order); // appel ajax au fichier ajax.php avec l'ordre des photos
		},
		stop: function() { jAlert('Opération terminée, élément déplacé', 'Message'); }
	});
	//$(".list_elements").disableSelection();
	
	//Autosize sur le td du tableau listing
	var autoSizeWidth = $('.auto_size_td').width();
	$('.auto_size_td').css({width: autoSizeWidth + 'px'});
	
	$("#InputSendMail").click(function() {
		
		if($("#InputSendMail:checked").length) { 
			jAlert("Attention en cochant cette case vous allez envoyer un email à l'ensemble des utilisateurs pour les informer de la modification"); 
		}
	});
	
	var leftHeight  = $(window).height() - 30;
	var leftUlHeight  = leftHeight - 70;
	$('#left').css({height:leftHeight});
	$('#left > ul').css({height:leftUlHeight});
	$('#left span.menu').toggle(
		function() { $('#left').animate({left: '+=250'}, 'slow'); },
		function() { $('#left').animate({left: '-=250'}, 'slow'); }
	);
});