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
		<?php
		/////////////
		//   CSS   //
		/////////////
		echo "\n";
		$css = array(
			'layout/'.$websiteParams['tpl_layout'].'/reset',
			'layout/'.$websiteParams['tpl_layout'].'/jquery-ui-1.9.1.custom',
			'layout/'.$websiteParams['tpl_layout'].'/style',
			'layout/'.$websiteParams['tpl_layout'].'/grids',
			'layout/'.$websiteParams['tpl_layout'].'/hook',
			'layout/'.$websiteParams['tpl_layout'].'/menu',			
			'layout/'.$websiteParams['tpl_layout'].'/superbuttons',
			'layout/'.$websiteParams['tpl_layout'].'/pagination',
			'layout/'.$websiteParams['tpl_layout'].'/prettyphoto',
			'layout/'.$websiteParams['tpl_layout'].'/table',
			'layout/'.$websiteParams['tpl_layout'].'/forms',
			//'layout/'.$websiteParams['tpl_layout'].'/uniform.default',
			'layout/'.$websiteParams['tpl_layout'].'/pricing',
			'layout/'.$websiteParams['tpl_layout'].'/footer',
			'layout/'.$websiteParams['tpl_layout'].'/colors/'.trim($websiteParams['tpl_code']).'/default',
			'layout/'.$websiteParams['tpl_layout'].'/colors/'.trim($websiteParams['tpl_code']).'/body'
		);			
		echo $helpers['Html']->css($css);		
		if(!empty($websiteParams['css_hack'])) { ?><style type="text/css"><?php echo $websiteParams['css_hack']; ?></style><?php }		
		?>
	</head>
	<body>
		<div class="wrap_content header">
			<?php $this->element($websiteParams['tpl_layout'].'/header'); ?>
			<?php $this->element($websiteParams['tpl_layout'].'/menu_general'); ?>		
	    </div>
	    <div class="wrap_content">
			<div class="main png_bg">
				<div class="inner_main">
					<?php echo $content_for_layout; ?>
				</div>
		    </div>
		    <div class="endmain png_bg"></div>
		</div>
		<div class="wrap_content">
			<?php $this->element($websiteParams['tpl_layout'].'/footer'); ?>
			<?php $this->element($websiteParams['tpl_layout'].'/logout'); ?>
		</div>				
		<?php 
		////////////////////
		//   JAVASCRIPT   //
		////////////////////		
		$js = array(
			'layout/'.$websiteParams['tpl_layout'].'/jquery-1.8.2',
			//'layout/'.$websiteParams['tpl_layout'].'/jquery-1.5.1.min',
			//'layout/'.$websiteParams['tpl_layout'].'/jquery-1.7.1.min',
			'layout/'.$websiteParams['tpl_layout'].'/jquery-ui-1.9.1.custom.min',
			'commun/scripts',
			'layout/'.$websiteParams['tpl_layout'].'/menu',
			'layout/'.$websiteParams['tpl_layout'].'/input',
			'layout/'.$websiteParams['tpl_layout'].'/plugins',
			'layout/'.$websiteParams['tpl_layout'].'/script',
			//'layout/'.$websiteParams['tpl_layout'].'/jquery.uniform',
			'layout/'.$websiteParams['tpl_layout'].'/jquery.filestyle.mini',
			'layout/'.$websiteParams['tpl_layout'].'/images_zoom',
			'layout/'.$websiteParams['tpl_layout'].'/pricing_table'
		);
		echo $helpers['Html']->js($js);		
		if(!empty($websiteParams['js_hack'])) { ?><script type="text/javascript"><?php echo $websiteParams['js_hack']; ?></script><?php }
		
		echo $helpers['Html']->analytics($websiteParams['ga_code']); 
		?>
	</body>
</html>