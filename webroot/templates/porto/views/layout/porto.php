<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
	<head>

		<!-- Basic -->
		<meta charset="utf-8">
		<?php 
		if(isset($title_for_layout) && !empty($title_for_layout)) { echo '<title>'.$title_for_layout.'</title>'; echo "\n"; } 
		if(isset($description_for_layout) && !empty($description_for_layout)) { echo "\t\t"; echo '<meta name="description" content="'.$description_for_layout.'" />'; echo "\n"; }
		if(isset($keywords_for_layout) && !empty($keywords_for_layout)) { echo "\t\t"; echo '<meta name="keywords" content="'.$keywords_for_layout.'" />'; echo "\n"; }
		if(isset($rss_for_layout) && !empty($rss_for_layout)) { echo "\t\t"; echo '<link rel="alternate" type="application/rss+xml" title="'.$rss_for_layout['title'].'" href="'.$rss_for_layout['link'].'" />'; echo "\n"; }
		if(isset($websiteParams['favicon']) && !empty($websiteParams['favicon'])) { echo "\t\t"; echo '<link rel="icon" type="image/png" href="'.$websiteParams['favicon'].'" />'; echo "\n"; }
		?>
		<meta name="generator" content="<?php echo GENERATOR_META; ?>" /><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE BALISE ?>

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">		
		<?php
		/////////////
		//   CSS   //
		/////////////
		echo "\n";
		$css = array(
			'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light',
			$websiteParams['tpl_layout'].'/vendor/bootstrap/bootstrap',
			$websiteParams['tpl_layout'].'/vendor/fontawesome/css/font-awesome',
			$websiteParams['tpl_layout'].'/vendor/owlcarousel/owl.carousel',
			$websiteParams['tpl_layout'].'/vendor/owlcarousel/owl.theme',
			$websiteParams['tpl_layout'].'/vendor/magnific-popup/magnific-popup',
			$websiteParams['tpl_layout'].'/css/theme',
			$websiteParams['tpl_layout'].'/css/theme-elements',
			$websiteParams['tpl_layout'].'/css/theme-blog',
			$websiteParams['tpl_layout'].'/css/theme-shop',
			$websiteParams['tpl_layout'].'/css/theme-animate',
			
			$websiteParams['tpl_layout'].'/vendor/circle-flip-slideshow/css/component',
			$websiteParams['tpl_layout'].'/vendor/nivo-slider/nivo-slider',
			$websiteParams['tpl_layout'].'/vendor/nivo-slider/default/default',
				
			$websiteParams['tpl_layout'].'/css/skins/default',
			$websiteParams['tpl_layout'].'/css/custom',
				
		);			
		echo $helpers['Html']->css($css);	
		echo $helpers['Html']->upload_additional_files('CSS');		
		if(!empty($websiteParams['css_hack'])) { ?><style type="text/css"><?php echo $websiteParams['css_hack']; ?></style><?php }		
		
		$js = array($websiteParams['tpl_layout'].'/vendor/modernizr/modernizr');
		echo $helpers['Html']->js($js);	
		?>
		<!--[if IE]>
			<link rel="stylesheet" href="<?php echo BASE_URL; ?>/templates/porto/css/ie.css">
		<![endif]-->

		<!--[if lte IE 8]>
			<script src="<?php echo BASE_URL; ?>/templates/porto/vendor/respond/respond.js"></script>
			<script src="<?php echo BASE_URL; ?>/templates/porto/vendor/excanvas/excanvas.js"></script>
		<![endif]-->
	</head>
	<body data-baseurl="<?php echo BASE_URL; ?>">

		<div class="body">
			<?php $this->element('header'); ?>			
			<div role="main" class="main"><?php echo $content_for_layout; ?></div>
			<?php $this->element('footer'); ?>
		</div>
		<?php		
		////////////////////
		//   JAVASCRIPT   //
		////////////////////		
		$js = array(
			$websiteParams['tpl_layout'].'/vendor/jquery/jquery',
			$websiteParams['tpl_layout'].'/vendor/jquery.appear/jquery.appear',
			$websiteParams['tpl_layout'].'/vendor/jquery.easing/jquery.easing',
			$websiteParams['tpl_layout'].'/vendor/jquery-cookie/jquery-cookie',
			$websiteParams['tpl_layout'].'/vendor/bootstrap/bootstrap',
			$websiteParams['tpl_layout'].'/vendor/common/common',
			$websiteParams['tpl_layout'].'/vendor/jquery.validation/jquery.validation',
			$websiteParams['tpl_layout'].'/vendor/jquery.stellar/jquery.stellar',
			$websiteParams['tpl_layout'].'/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart',
			$websiteParams['tpl_layout'].'/vendor/jquery.gmap/jquery.gmap',
			//$websiteParams['tpl_layout'].'/vendor/twitterjs/twitter',
			$websiteParams['tpl_layout'].'/vendor/isotope/jquery.isotope',
			$websiteParams['tpl_layout'].'/vendor/owlcarousel/owl.carousel',
			$websiteParams['tpl_layout'].'/vendor/jflickrfeed/jflickrfeed',
			$websiteParams['tpl_layout'].'/vendor/magnific-popup/jquery.magnific-popup',
			$websiteParams['tpl_layout'].'/vendor/vide/jquery.vide',
			$websiteParams['tpl_layout'].'/js/theme',
			
			$websiteParams['tpl_layout'].'/vendor/circle-flip-slideshow/js/jquery.flipshow',
			$websiteParams['tpl_layout'].'/vendor/nivo-slider/jquery.nivo.slider',
			$websiteParams['tpl_layout'].'/js/views/view.home',
			
			$websiteParams['tpl_layout'].'/js/custom',
			$websiteParams['tpl_layout'].'/js/theme.init',
		);
		echo $helpers['Html']->js($js);	
		echo $helpers['Html']->upload_additional_files('JS');	
		if(!empty($websiteParams['js_hack'])) { ?><script type="text/javascript"><?php echo $websiteParams['js_hack']; ?></script><?php }
		
		echo $helpers['Html']->analytics($websiteParams['ga_code']); 
		?>
	</body>
</html>