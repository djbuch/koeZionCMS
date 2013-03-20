<div id="left">
	<ul>
		<li><a href="<?php echo Router::url('backoffice/dashboard'); ?>"><?php echo _("Tableau de bord"); ?></a></li>
		<?php 
		foreach($leftMenus as $k => $v) {

			if(count($v['menus'])) {		
					
				?>
				<li>
					<?php if(!empty($v['libelle'])) { ?><a><?php echo $v['libelle']; ?></a><?php } ?>
					<ul>
						<?php foreach($v['menus'] as $leftMenus) { ?>
							<li><a href="<?php echo Router::url('backoffice/'.$leftMenus['controller_name'].'/index'); ?>"><?php echo $leftMenus['name']; ?></a></li>
						<?php } ?>
					</ul>
				</li>
				<?php 
			}
		} 
		?>
	<div id="credits">&#169; Copyright 2011 koéZionCMS</div>
</div>