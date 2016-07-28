$(document).ready(function() {
		
	$('aside.main-sidebar li.active').parents('li').addClass('active');
	
	// CHECK ALL PAGES
    $('.checkall').click(function() {
    	
    	var checkbox = $(this).parents('table').find(':checkbox');    	
        checkbox.attr('checked', this.checked);
    });	
		
	$('[data-toggle="popover"]').livequery(function() { 	

		$(this).popover({'trigger': 'hover'});
	});
   
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
		}
	});
	
	$(".list_elements .sortable td").each(function() {
		
		var width = $(this).outerWidth();
		$(this).css('width', width);		
	});
	
    $('.datepicker').livequery(function() { 	

		$(this).daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			format: "DD.MM.YYYY"
		});
	});    

	$("#InputFilterTemplate").change(function() {
	
		var value = $(this).val();
		var host = $.get_host(); //Récupération du host
		var action = host + 'websites/ajax_get_templates/' + value + '.html'; //On génère l'url
								
		/*console.log('HOST --> ' + host);
		console.log('numArticle --> ' + numArticle);
		console.log('action --> ' + action);*/
					
		$.get(action, function(datas) { //On récupère les données en GET
			
			$("#tpl .templates_table").replaceWith(datas); //On rajoute une nouvelle ligne
		});			
	});	
	
	//Diffusion d'un article sur tous les sites
	$("#btnDisplayPostAllWebsites").click(function() {
		
		if($(this).hasClass('check_all')) { 
			$('.display_on_website').attr('checked', 'checked');
			$(this).removeClass('check_all').addClass('uncheck_all');
		} else if($(this).hasClass('uncheck_all')) { 			
			$('.display_on_website').attr('checked', false);
			$(this).removeClass('uncheck_all').addClass('check_all');			
		}
		return false;
	});
});