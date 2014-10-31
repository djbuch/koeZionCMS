<?php echo $helpers['Html']->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php 
		if(isset($title_for_layout) && !empty($title_for_layout)) { echo '<title>'.$title_for_layout.'</title>'; echo "\n"; } 
		if(isset($description_for_layout) && !empty($description_for_layout)) { echo "\t\t"; echo '<meta name="description" content="'.$description_for_layout.'" />'; echo "\n"; }
		if(isset($keywords_for_layout) && !empty($keywords_for_layout)) { echo "\t\t"; echo '<meta name="keywords" content="'.$keywords_for_layout.'" />'; echo "\n"; }
		if(isset($rss_for_layout) && !empty($rss_for_layout)) { echo "\t\t"; echo '<link rel="alternate" type="application/rss+xml" title="'.$rss_for_layout['title'].'" href="'.$rss_for_layout['link'].'" />'; echo "\n"; }
		if(isset($websiteParams['favicon']) && !empty($websiteParams['favicon'])) { echo "\t\t"; echo '<link rel="icon" type="image/png" href="'.$websiteParams['favicon'].'" />'; echo "\n"; }
		?>
		<meta name="generator" content="<?php echo GENERATOR_META; ?>" /><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE BALISE ?>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php
		/////////////
		//   CSS   //
		/////////////
		echo "\n";
		$css = array();			
		echo $helpers['Html']->css($css);	
		if(!empty($websiteParams['css_hack_file'])) { ?><link href="<?php echo $websiteParams['css_hack_file']; ?>" rel="stylesheet" type="text/css" media="all" /><?php }		
		?>
	</head>
	<body data-baseurl="<?php echo BASE_URL; ?>">		
		<?php 
		$this->element('header'); 
		$this->element('menu_general');
		echo $content_for_layout;
		$this->element('footer'); 
		
		////////////////////
		//   JAVASCRIPT   //
		////////////////////		
		$js = array();
		echo $helpers['Html']->js($js);	
		if(!empty($websiteParams['js_hack_file'])) { ?><script src="<?php echo $websiteParams['js_hack_file']; ?>" type="text/javascript"></script><?php }
		
		echo $helpers['Html']->analytics($websiteParams['ga_code']); 
		?>
	</body>
</html>