<?php echo $helpers['Html']->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php 
		if(isset($title_for_layout) && !empty($title_for_layout)) { echo '<title>'.$title_for_layout.'</title>'; echo "\n"; } 
		if(isset($description_for_layout) && !empty($description_for_layout)) { echo "\t\t"; echo '<meta name="description" content="'.$description_for_layout.'" />'; echo "\n"; }
		if(isset($keywords_for_layout) && !empty($keywords_for_layout)) { echo "\t\t"; echo '<meta name="keywords" content="'.$keywords_for_layout.'" />'; echo "\n"; }
		if(isset($rss_for_layout) && !empty($rss_for_layout)) { echo "\t\t"; echo '<link rel="alternate" type="application/rss+xml" title="'.$rss_for_layout['title'].'" href="'.$rss_for_layout['link'].'" />'; echo "\n"; }
		?>
		<meta name="generator" content="<?php echo GENERATOR_META; ?>" /><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE BALISE ?>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php
		/////////////
		//   CSS   //
		/////////////
		echo "\n";
		$css = array(
			$websiteParams['tpl_layout'].'/css/reset',
			$websiteParams['tpl_layout'].'/css/jquery-ui-1.9.1.custom',
			$websiteParams['tpl_layout'].'/css/style',
			$websiteParams['tpl_layout'].'/css/grids',
			$websiteParams['tpl_layout'].'/css/hook',
			$websiteParams['tpl_layout'].'/css/menu',			
			$websiteParams['tpl_layout'].'/css/buttons',
			$websiteParams['tpl_layout'].'/css/pagination',
			$websiteParams['tpl_layout'].'/css/prettyphoto',
			$websiteParams['tpl_layout'].'/css/table',
			$websiteParams['tpl_layout'].'/css/forms',
			//$websiteParams['tpl_layout'].'/css/uniform.default',
			$websiteParams['tpl_layout'].'/css/pricing',
			$websiteParams['tpl_layout'].'/css/footer',
			$websiteParams['tpl_layout'].'/css/colors/'.trim($websiteParams['tpl_code']).'/default',
			$websiteParams['tpl_layout'].'/css/colors/'.trim($websiteParams['tpl_code']).'/body',
			$websiteParams['tpl_layout'].'/css/colors/'.trim($websiteParams['tpl_code']).'/buttons',
			$websiteParams['tpl_layout'].'/css/theme_responsive',
			$websiteParams['tpl_layout'].'/css/font-awsome'
		);			
		echo $helpers['Html']->css($css);		
		if(!empty($websiteParams['css_hack'])) { ?><style type="text/css"><?php echo $websiteParams['css_hack']; ?></style><?php }		
		?>
	</head>
	<body>
	
		<?php if(isset($plugin_ads)) { $this->element(PLUGINS.DS.'ads/views/elements/ads/frontoffice/ads', null, false); } ?>
		<div class="wrap_content header">
			<?php $this->element('header'); ?>
			<?php $this->element('menu_general'); ?>		
		</div>
		<div class="wrap_content">
			<div class="main png_bg">
				<div class="inner_main">
					<?php echo $content_for_layout; ?>
				</div>
				<div class="fake_foot"></div>
			</div>
			<div class="main_left"></div>
			<div class="main_right"></div>
			<div class="endmain png_bg">
			<div class="endmain_center_left"></div>
			<div class="endmain_center_right"></div>
			<div class="endmain_right"></div>
			</div>
		</div>
		<div class="wrap_content">
			<?php $this->element('footer'); ?>
			<?php $this->element('logout'); ?>
		</div>				
		<?php 
		////////////////////
		//   JAVASCRIPT   //
		////////////////////		
		$js = array(
			$websiteParams['tpl_layout'].'/js/jquery-1.8.2',
			//$websiteParams['tpl_layout'].'/js/jquery-1.5.1.min',
			//$websiteParams['tpl_layout'].'/js/jquery-1.7.1.min',
			$websiteParams['tpl_layout'].'/js/jquery-ui-1.9.1.custom.min',
			$websiteParams['tpl_layout'].'/js/jquery.ba-throttle-debounce',
			'/commun/scripts',
			$websiteParams['tpl_layout'].'/js/menu',
			$websiteParams['tpl_layout'].'/js/input',
			$websiteParams['tpl_layout'].'/js/plugins',
			$websiteParams['tpl_layout'].'/js/script',
			//$websiteParams['tpl_layout'].'/js/jquery.uniform',
			$websiteParams['tpl_layout'].'/js/jquery.filestyle.mini',
			$websiteParams['tpl_layout'].'/js/images_zoom',
			$websiteParams['tpl_layout'].'/js/pricing_table',
			$websiteParams['tpl_layout'].'/js/navigation/selectnav',
			$websiteParams['tpl_layout'].'/js/scrollToTop',
			$websiteParams['tpl_layout'].'/js/browserSelector',
			$websiteParams['tpl_layout'].'/js/theme',
			$websiteParams['tpl_layout'].'/js/fittext/jquery.fittext'
		);
		echo $helpers['Html']->js($js);		
		if(!empty($websiteParams['js_hack'])) { ?><script type="text/javascript"><?php echo $websiteParams['js_hack']; ?></script><?php }
		
		echo $helpers['Html']->analytics($websiteParams['ga_code']); 
		?>

		<script>
			$(document).ready(function(){
				$(function(){
					jQuery("#title404").fitText(0.22, { maxFontSize: '200px'});
				});
			});
		</script>
		<span class="websitebaseurl" style="display:none;"><?php echo BASE_URL; ?></span>
	</body>
</html>