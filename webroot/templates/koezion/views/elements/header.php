<div id="header">
	<div id="logo"><?php echo $websiteParams['tpl_logo']; ?></div>
	<?php 
		if(isset($websiteParams['search_engine_position']) && $websiteParams['search_engine_position'] == 'header') {
			
			$this->element('search'); 
		} 
	?>
</div>