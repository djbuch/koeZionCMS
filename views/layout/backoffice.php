<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php //echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo _(".:: Backoffice ::."); ?></title>
		
		<?php
		$css = array(
			'backoffice/style',
			'backoffice/style_text',
			'backoffice/link-buttons', 		//Skin pour les liens boutons
			//'backoffice/fullcalendar', 	//Pour la gestion d'un calendrier
			'backoffice/forms', 			//Skin pour les éléments de formulaires
			'backoffice/form-buttons', 		//Skin pour les boutons de formulaires
			//'backoffice/accordion',		//Skin menu accordéons
			//'backoffice/modalwindow',		//Pour l'affichage de message de notification 
			'backoffice/system-messages', 	//Skin pour les messages d'erreurs et autre
			'backoffice/datatable', 		//Skin pour les tableaux
			'backoffice/pagination',		//Skin pour la pagination
			//'backoffice/statics', 		//Pour les graphes
			//'backoffice/tabs',
			'backoffice/alerts',			//Messages en popup (Confirmation de suppression par exemple avec jquery.alerts)
			'backoffice/tooltip',			//Skin des tooltips
			//'backoffice/notifications', 	//Pour l'affichage de message de notification (JGrowl)
			'backoffice/labels', 			//Pour l'affichage de lable (online par exemple)
			//'backoffice/prettify', 		//Pour l'affichage du code source
			//'backoffice/elfinder', 		//Pour la gestion du module de gestion de fichiers
			//'backoffice/pirebox', 		//Pour la mise en place de zoom sur les images
			//'backoffice/colorpicker', 	//Pour la mise en place de zone de sélection de couleurs
			//'backoffice/wizard',			//Pour la mise en place des tabs avec des boutons next, prev
			'backoffice/smart_tab',			//Pour la mise en place des smarttabs
			'backoffice/hook',
			'backoffice/prettyradio'
		);
		echo $helpers['Html']->css($css);		
		
		$js = array(
			'backoffice/jquery-1.7.1.min', 				//Librairie JQuery
			'backoffice/jquery-ui',						//Librairie JQueryUI
			'backoffice/jquery-ui-select', 				//Pour les listes déroulantes
			'backoffice/jquery.customInput', 			//Pour les cases à cocher et les boutons radio
			'backoffice/jquery-ui-spinner', 			//Utilisé pour la mise en place de loader et nécessaire au fonctionnement des alertes personnalisées
			'backoffice/jquery.alerts', 				//Pour la mise en place des alertes personnalisées
			//'backoffice/jquery.dataTables', 			//Pour la mise en place de tris sur les tableaux
			//'backoffice/jquery.smartwizard-2.0.min',	//Pour la mise en place des tabs avec des boutons next, prev			
			'backoffice/jquery.smartTab',				//Pour la mise en place des smarttabs			
			//'backoffice/jquery.flot', 				//Pour les graphes
			//'backoffice/jquery.graphtable-0.2', 		//Pour les graphes
			//'backoffice/jquery.flot.pie.min', 		//Pour les graphes
			//'backoffice/jquery.flot.resize.min', 		//Pour les graphes
			'backoffice/jquery.filestyle.mini', 		//Pour les champs d'upload dans les formulaires
			//'backoffice/prettify', 					//Pour l'affichage du code source
			//'backoffice/elfinder.min', 				//Pour la gestion du module de gestion de fichiers
			//'backoffice/jquery.jgrowl', 				//Pour l'affichage de message de notification 
			//'backoffice/colorpicker', 				//Pour la mise en place de zone de sélection de couleurs
			'backoffice/jquery.tipsy', 					//Pour la mise en place des tooltips
			//'backoffice/fullcalendar.min', 			//Pour la gestion d'un calendrier
			//'backoffice/pirobox.extended.min', 		//Pour la mise en place de zoom sur les images
			//'backoffice/jquery.validate.min', 		//Pour la mise en place de validation via jquery
			//'backoffice/jquery.metadata', 			//Utile pour pouvoir passer des paramètres au javascript directement dans les attributs de l'élément html
			'backoffice/costum', 						//Appel des différents plugins
			'backoffice/default', 						//Fonctions personnalisées
			'ckeditor/ckeditor', 						//Librairie CKEditor
			'ckfinder/ckfinder', 						//Librairie CKFinder
			'backoffice/prettyradio' 						//Librairie CKFinder
		);
		echo $helpers['Html']->js($js);
		?>
	</head>

	<body>
		<div id="wrapper">
			
			<?php $this->element('backoffice/left'); ?>
			
			<div id="right">
			
				<?php $this->element('backoffice/top_bar'); ?>
				<?php //$this->element('backoffice/breadcrumbs'); ?>
				
				<div id="main">
					<?php
					$this->element('backoffice/flash_messages');
					echo $content_for_layout; 
					?>
				</div>
			</div>
		</div>
	</body>
</html>