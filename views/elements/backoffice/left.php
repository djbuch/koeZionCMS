<div id="left">
	<ul>
		<?php /* ?><li class="active"><a href="<?php echo Router::url('backoffice/home/dashboard'); ?>"><?php echo _("Accueil"); ?></a></li><?php */ ?>		
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
		
		
		<?php /* ?><li><a href="<?php echo Router::url('backoffice/categories/index'); ?>"><?php echo _("Catégories"); ?></a>		
		<li><a href="<?php echo Router::url('backoffice/sliders/index'); ?>"><?php echo _("Sliders"); ?></a></li>
		<li><a href="<?php echo Router::url('backoffice/focus/index'); ?>"><?php echo _("Focus"); ?></a></li>
		<li><a href="<?php echo Router::url('backoffice/right_buttons/index'); ?>"><?php echo _("Boutons colonne droite"); ?></a></li>
		<li>
			<a><?php echo _("ARTICLES"); ?></a>
			<ul>
				<li><a href="<?php echo Router::url('backoffice/posts/index'); ?>"><?php echo _("Articles"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/posts_types/index'); ?>"><?php echo _("Type d'articles"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>"><?php echo _("Commentaires articles"); ?></a></li>
			</ul>			
		</li>		
		<li><a href="<?php echo Router::url('backoffice/contacts/index'); ?>"><?php echo _("Contacts"); ?></a></li>	
		<li><a href="<?php echo Router::url('backoffice/websites/index'); ?>"><?php echo _("Sites Internet"); ?></a></li>	
		<li><a href="<?php echo Router::url('backoffice/users/index'); ?>"><?php echo _("Utilisateurs"); ?></a></li>	
		<li><a href="<?php echo Router::url('backoffice/plugins/index'); ?>"><?php echo _("Plugins"); ?></a></li>	
		<?php if(isset($activatePlugins) && !empty($activatePlugins)) { ?>	
			<li>
				<a></a>
				<ul>
					<?php 
					foreach($activatePlugins as $k => $v) {

						if($this->backoffice_index_for_plugin($v['code'], 'backoffice_index')) {
							
							?><li><a href="<?php echo Router::url('backoffice/'.$v['code'].'/index'); ?>"><?php echo $v['name']; ?></a></li><?php
						} 
					} 
					?>
				</ul>			
			</li>	
		<?php }  ?><?php */ ?>	
	</ul>	
	<?php /* ?><div id="credits">&#169; Copyright <?php echo date('Y')?> koéZionCMS</div><?php */ ?>
	<div id="credits">&#169; Copyright 2011 koéZionCMS</div>
</div>