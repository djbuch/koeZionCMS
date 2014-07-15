<nav>		
	<?php 
	if(!isset($breadcrumbs)) $breadcrumbs = array();
	$helpers['Nav']->generateMenu($menuGeneral, $breadcrumbs); 
	?>
	<?php if(isset($websiteParams['search_engine_position']) && $websiteParams['search_engine_position'] == 'menu') { $this->element('search'); } ?>
</nav>