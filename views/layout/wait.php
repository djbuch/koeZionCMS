<?php echo $helpers['Html']->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php if(isset($title_for_layout) && !empty($title_for_layout)) { ?><title><?php echo $title_for_layout; ?></title><?php echo "\n"; } ?>
		<?php if(isset($description_for_layout) && !empty($description_for_layout)) { ?><meta name="description" content="<?php echo $description_for_layout; ?>" /><?php echo "\n"; } ?>
		<?php if(isset($keywords_for_layout) && !empty($keywords_for_layout)) { ?><meta name="keywords" content="<?php echo $keywords_for_layout; ?>" /><?php echo "\n"; } ?>			
		<meta name="generator" content="<?php echo GENERATOR_META; ?>" /><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE BALISE ?>		
		<?php
		echo "\n";
		$css = array(
			'frontoffice/reset',
			'frontoffice/style',
			'frontoffice/grids',
			'frontoffice/hook',
			'frontoffice/menu',
			'frontoffice/nivo_slider',
			'frontoffice/superbuttons',
			'frontoffice/pagination',
			'frontoffice/prettyphoto',
			'frontoffice/table',
			'frontoffice/forms',
			'frontoffice/pricing',
			'frontoffice/footer',
			'frontoffice/colors/'.trim($websiteParams['tpl_code']).'/default',
			'frontoffice/colors/'.trim($websiteParams['tpl_code']).'/body'
		);		
		
		//On va vérifier si un dossier header est présent dans le dossier upload/images/header
		//Si tel est le cas on va récupérer l'ensemble des fichiers présent puis compter qu'il y en ait au moins un
		//Ensuite on va afficher le css qui gère le fond du header
		//On ne fera rien par défaut
		$headerDir = directoryContent(WEBROOT.DS.'upload'.DS.'images'.DS.'header');
		if(count($headerDir) > 0) { $css[] = 'frontoffice/hook_header'; }
		
		echo $helpers['Html']->css($css, true);
		
		$js = array(
			'frontoffice/jquery-1.5.1.min',
			'frontoffice/plugins',
			'frontoffice/script',
			'frontoffice/images_zoom',
			'frontoffice/pricing_table',
		);
		echo $helpers['Html']->js($js);
				
		echo $helpers['Html']->analytics($websiteParams['ga_code']);
		?>
	</head>

	<?php /* ?><body onload="prettyPrint()"><?php */ ?>	
	<body>
		<div id="container" class="wait">	    
			<div class="startmain png_bg"></div>
			<div class="main png_bg">
				<div class="inner_main">
					<?php echo $content_for_layout; ?>
				</div>
		    </div>
		    <div class="endmain png_bg"></div>
		</div>
	</body>
</html>