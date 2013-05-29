<div id="menu" class="png_bg">	
	<div class="menu_center_left"></div>		
	<div class="menu_center_right"></div>		
	<div class="menu_right"></div>		
	<?php 
	if(!isset($breadcrumbs)) $breadcrumbs = array();
	$helpers['Html']->generateMenu($menuGeneral, $breadcrumbs); 
	?>
	<?php if(isset($websiteParams['search_engine_position']) && $websiteParams['search_engine_position'] == 'menu') { $this->element($websiteParams['tpl_layout'].'/search'); } ?>
</div>