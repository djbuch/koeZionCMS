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
			$websiteParams['tpl_layout'].'/reset',
			$websiteParams['tpl_layout'].'/jquery-ui-1.9.1.custom',
			$websiteParams['tpl_layout'].'/style',
			$websiteParams['tpl_layout'].'/grids',
			$websiteParams['tpl_layout'].'/hook',
			$websiteParams['tpl_layout'].'/menu',
			$websiteParams['tpl_layout'].'/nivo_slider',
			$websiteParams['tpl_layout'].'/superbuttons',
			$websiteParams['tpl_layout'].'/pagination',
			$websiteParams['tpl_layout'].'/prettyphoto',
			$websiteParams['tpl_layout'].'/table',
			$websiteParams['tpl_layout'].'/forms',
			//$websiteParams['tpl_layout'].'/uniform.default',
			$websiteParams['tpl_layout'].'/pricing',
			$websiteParams['tpl_layout'].'/footer',
			$websiteParams['tpl_layout'].'/colors/'.trim($websiteParams['tpl_code']).'/default',
			$websiteParams['tpl_layout'].'/colors/'.trim($websiteParams['tpl_code']).'/body',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shCore',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shCoreDefault'
		);			
		echo $helpers['Html']->css($css, true);		
		if(!empty($websiteParams['css_hack'])) { ?><style type="text/css"><?php echo $websiteParams['css_hack']; ?></style><?php }		
		?>
	</head>
	<body>
		<div id="container">
			<?php $this->element('frontoffice/header'); ?>
			<?php $this->element('frontoffice/menu_general'); ?>		
	    
			<div class="main png_bg">
				<div class="inner_main">
					<?php echo $content_for_layout; ?>
				</div>
		    </div>
		    <div class="endmain png_bg"></div>
		
			<?php $this->element('frontoffice/footer'); ?>
			<?php $this->element('frontoffice/logout'); ?>
		</div>				
		<?php 
		////////////////////
		//   JAVASCRIPT   //
		////////////////////		
		$js = array(
			//$websiteParams['tpl_layout'].'/jquery-1.8.2',
			$websiteParams['tpl_layout'].'/jquery-1.5.1.min',
			//$websiteParams['tpl_layout'].'/jquery-1.7.1.min',
			$websiteParams['tpl_layout'].'/jquery-ui-1.9.1.custom.min',
			'commun/scripts',
			$websiteParams['tpl_layout'].'/menu',
			$websiteParams['tpl_layout'].'/input',
			$websiteParams['tpl_layout'].'/plugins',
			$websiteParams['tpl_layout'].'/script',
			//$websiteParams['tpl_layout'].'/jquery.uniform',
			$websiteParams['tpl_layout'].'/jquery.filestyle.mini',
			$websiteParams['tpl_layout'].'/images_zoom',
			$websiteParams['tpl_layout'].'/pricing_table',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shCore',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushCss',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushJScript',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushPhp',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushPlain',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushSql',
			$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushXml'
		);
		echo $helpers['Html']->js($js);
		?>
     	<script type="text/javascript">	
     		SyntaxHighlighter.all()
		</script>
		<?php echo $helpers['Html']->analytics($websiteParams['ga_code']); ?>
	</body>
</html>