<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	    <meta name="generator" content="<?php echo GENERATOR_META; ?>" /><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE BALISE ?>
		<?php 
		if(isset($title_for_layout) && !empty($title_for_layout)) { echo '<title>'.$title_for_layout.'</title>'; echo "\n"; } 
		if(isset($description_for_layout) && !empty($description_for_layout)) { echo "\t\t"; echo '<meta name="description" content="'.$description_for_layout.'" />'; echo "\n"; }
		if(isset($keywords_for_layout) && !empty($keywords_for_layout)) { echo "\t\t"; echo '<meta name="keywords" content="'.$keywords_for_layout.'" />'; echo "\n"; }
		if(isset($rss_for_layout) && !empty($rss_for_layout)) { echo "\t\t"; echo '<link rel="alternate" type="application/rss+xml" title="'.$rss_for_layout['title'].'" href="'.$rss_for_layout['link'].'" />'; echo "\n"; }
		if(isset($websiteParams['favicon']) && !empty($websiteParams['favicon'])) { echo "\t\t"; echo '<link rel="icon" type="image/png" href="'.$websiteParams['favicon'].'" />'; echo "\n"; }
		
		/////////////
		//   CSS   //
		/////////////
		echo "\n";
		$css = array(
			$websiteParams['tpl_layout'].'/assets/bootstrap-3.3.6-dist/css/bootstrap',
			$websiteParams['tpl_layout'].'/assets/bootstrap-3.3.6-dist/css/bootstrap-theme',
			$websiteParams['tpl_layout'].'/assets/smartmenus-1.0.0/addons/bootstrap/jquery.smartmenus.bootstrap',
			$websiteParams['tpl_layout'].'/css/all'
		);			
		if(!empty($websiteParams['css_hack_file'])) { $css[] = 'F/'.$websiteParams['css_hack_file']; } //Chargement des CSS complémentaires		
		echo $helpers['Html']->css($css);			
		?>
    </head>
    <body data-baseurl="<?php echo BASE_URL; ?>" data-template="<?php echo BASE_URL; ?>/templates/<?php echo $websiteParams['tpl_layout']; ?>/">
        <?php 
        //pr(array_keys($this->controller->get('vars')));
        $this->element('header'); ?>
		<section class="content">
			<?php echo $content_for_layout; ?>
		</section>
		<?php 
		$this->element('newsletter'); 
		$this->element('footer'); 
		$this->element('logout'); 
		
		////////////////////
		//   JAVASCRIPT   //
		////////////////////		
		$js = array(
			'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',		
			'/commun/scripts',
			$websiteParams['tpl_layout'].'/assets/bootstrap-3.3.6-dist/js/bootstrap',	
			$websiteParams['tpl_layout'].'/assets/smartmenus-1.0.0/jquery.smartmenus',
			'http://maps.google.com/maps/api/js?sensor=false&amp;language=en',	
			$websiteParams['tpl_layout'].'/assets/gmap3',
			$websiteParams['tpl_layout'].'/assets/masonry.pkgd.min',
			$websiteParams['tpl_layout'].'/assets/imagesloaded.pkgd.min',
			$websiteParams['tpl_layout'].'/js/all'
		);
		if(!empty($websiteParams['js_hack_file'])) { $js[] = 'F/'.$websiteParams['js_hack_file']; } //Chargement des JS complémentaires
		echo $helpers['Html']->js($js);		
		echo $helpers['Html']->analytics($websiteParams['ga_code']); 
		?>  
    </body>
</html>