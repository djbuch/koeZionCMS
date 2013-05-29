<div id="left">
	<ul>
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
	</ul>
	<div id="credits">&#169; Copyright 2011 koéZionCMS</div>
</div>