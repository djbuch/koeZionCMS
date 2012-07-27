<div id="menu" class="png_bg">			
	<?php 
	if(!isset($breadcrumbs)) $breadcrumbs = array();
	$helpers['Html']->generateMenu($menuGeneral, $breadcrumbs); 
	?>
	<?php if(isset($websiteParams['search_engine_position']) && $websiteParams['search_engine_position'] == 'menu') { $this->element('frontoffice/search'); } ?>
</div>