<div id="left">
	<ul>
		<?php /* ?><li class="active"><a href="<?php echo Router::url('backoffice/home/dashboard'); ?>"><?php echo _("Accueil"); ?></a></li><?php */ ?>		
		<li>
			<a><?php echo _("CATEGORIES"); ?></a>
			<ul>
				<li><a href="<?php echo Router::url('backoffice/categories/index'); ?>"><?php echo _("Catégories"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/categories_sliders/index'); ?>"><?php echo _("Sliders Catégories"); ?></a></li>
			</ul>			
		</li>		
		
		<li><a href="<?php echo Router::url('backoffice/sliders/index'); ?>"><?php echo _("Sliders"); ?></a></li>
		<li><a href="<?php echo Router::url('backoffice/focus/index'); ?>"><?php echo _("Focus"); ?></a></li>
		<li>
			<a><?php echo _("ARTICLES"); ?></a>
			<ul>
				<li><a href="<?php echo Router::url('backoffice/posts/index'); ?>"><?php echo _("Articles"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/posts_types/index'); ?>"><?php echo _("Type d'articles"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>"><?php echo _("Commentaires articles"); ?></a></li>
			</ul>			
		</li>			
		<li><a href="<?php echo Router::url('backoffice/contacts/index'); ?>"><?php echo _("Contacts"); ?></a></li>	
		<?php if(isset($activatePlugins) && !empty($activatePlugins)) { ?>	
			<li>
				<a><?php echo _("PLUGINS"); ?></a>
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
		<?php }  ?>
	</ul>	
	<div id="credits">&#169; Copyright <?php echo date('Y')?> koéZionCMS</div>
</div>