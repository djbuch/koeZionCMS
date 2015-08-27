<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php //echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo ".:: "._("Système d'administration")." | ".$this->params['controllerName']." - ".$this->params['action']." ::."; ?></title>
		
		<?php
		$css = array(
			'bo_godzilla/css/style',
			'bo_godzilla/css/style_text',
			'bo_godzilla/css/link-buttons', 					//Skin pour les liens boutons
			//'bo_godzilla/css/fullcalendar', 					//Pour la gestion d'un calendrier
			'bo_godzilla/css/forms', 							//Skin pour les éléments de formulaires
			'bo_godzilla/css/form-buttons', 					//Skin pour les boutons de formulaires
			//'bo_godzilla/css/accordion',						//Skin menu accordéons
			//'bo_godzilla/css/modalwindow',					//Pour l'affichage de message de notification 
			'bo_godzilla/css/system-messages', 					//Skin pour les messages d'erreurs et autre
			'bo_godzilla/css/datatable', 						//Skin pour les tableaux
			'bo_godzilla/css/pagination',						//Skin pour la pagination
			//'bo_godzilla/css/statics', 						//Pour les graphes
			//'bo_godzilla/css/tabs',
			'bo_godzilla/css/alerts',							//Messages en popup (Confirmation de suppression par exemple avec jquery.alerts)
			'bo_godzilla/css/tooltip',							//Skin des tooltips
			//'bo_godzilla/css/notifications', 					//Pour l'affichage de message de notification (JGrowl)
			'bo_godzilla/css/labels', 							//Pour l'affichage de lable (online par exemple)
			//'bo_godzilla/css/prettify', 						//Pour l'affichage du code source
			//'bo_godzilla/css/elfinder', 						//Pour la gestion du module de gestion de fichiers
			//'bo_godzilla/css/pirebox', 						//Pour la mise en place de zoom sur les images
			'bo_godzilla/css/colorpicker', 						//Pour la mise en place de zone de sélection de couleurs
			//'bo_godzilla/css/wizard',							//Pour la mise en place des tabs avec des boutons next, prev
			'bo_godzilla/css/smart_tab',						//Pour la mise en place des smarttabs
			'bo_godzilla/css/hook',
			'bo_godzilla/css/prettyradio',
			'bo_godzilla/css/jquery-ui-1.10.3.custom',			//JqueryUI pour autocomplete
			'/commun/context_menu/jquery.contextMenu'		//Menu contextuel sur le clic droit
		);
		echo $helpers['Html']->css($css);

		$js = array(
			'bo_godzilla/js/jquery-1.7.1.min', 						//Librairie JQuery
			'bo_godzilla/js/jquery-ui',								//Librairie JQueryUI
			'bo_godzilla/js/jquery-ui-select', 						//Pour les listes déroulantes
			'/commun/jquery.livequery', 							//Utile pour le chargement des pages en ajax
			'/commun/context_menu/jquery.ui.position', 	//Menu contextuel sur le clic droit
			'/commun/context_menu/jquery.contextMenu', 	//Menu contextuel sur le clic droit
			'/commun/scripts', 										//Pour les listes déroulantes
			'bo_godzilla/js/jquery.customInput', 						//Pour les cases à cocher et les boutons radio
			'bo_godzilla/js/jquery-ui-spinner', 						//Utilisé pour la mise en place de loader et nécessaire au fonctionnement des alertes personnalisées
			'bo_godzilla/js/jquery.alerts', 							//Pour la mise en place des alertes personnalisées
			//'bo_godzilla/js/jquery.dataTables', 						//Pour la mise en place de tris sur les tableaux
			//'bo_godzilla/js/jquery.smartwizard-2.0.min',				//Pour la mise en place des tabs avec des boutons next, prev			
			'bo_godzilla/js/jquery.smartTab',							//Pour la mise en place des smarttabs			
			//'bo_godzilla/js/jquery.flot', 							//Pour les graphes
			//'bo_godzilla/js/jquery.graphtable-0.2', 					//Pour les graphes
			//'bo_godzilla/js/jquery.flot.pie.min', 					//Pour les graphes
			//'bo_godzilla/js/jquery.flot.resize.min', 				//Pour les graphes
			'bo_godzilla/js/jquery.filestyle.mini', 					//Pour les champs d'upload dans les formulaires
			//'bo_godzilla/js/prettify', 								//Pour l'affichage du code source
			//'bo_godzilla/js/elfinder.min', 							//Pour la gestion du module de gestion de fichiers
			//'bo_godzilla/js/jquery.jgrowl', 							//Pour l'affichage de message de notification 
			'bo_godzilla/js/colorpicker', 								//Pour la mise en place de zone de sélection de couleurs
			'bo_godzilla/js/jquery.tipsy', 							//Pour la mise en place des tooltips
			//'bo_godzilla/js/fullcalendar.min', 						//Pour la gestion d'un calendrier
			//'bo_godzilla/js/pirobox.extended.min', 					//Pour la mise en place de zoom sur les images
			//'bo_godzilla/js/jquery.validate.min', 					//Pour la mise en place de validation via jquery
			//'bo_godzilla/js/jquery.metadata', 						//Utile pour pouvoir passer des paramètres au javascript directement dans les attributs de l'élément html
			'bo_godzilla/js/costum', 									//Appel des différents plugins
			'bo_godzilla/js/default', 									//Fonctions personnalisées
			'/../ck/ckeditor/ckeditor', 									//Librairie CKEditor
			'/../ck/ckfinder/ckfinder', 									//Librairie CKFinder
			'bo_godzilla/js/prettyradio' 								//
		);
		echo $helpers['Html']->js($js);
		?>
	</head>

	<body data-baseurl="<?php echo BASE_URL; ?>">
		<?php $this->element('top_bar'); ?>
		<?php $this->element('left'); ?>		
		<div id="wrapper">
			<div id="right">				
				<?php //$this->element('breadcrumbs'); ?>				
				<div id="main">
					<?php
					$this->element('flash_messages');
					echo $content_for_layout; 
					?>
				</div>
			</div>
		</div>
	</body>
</html>