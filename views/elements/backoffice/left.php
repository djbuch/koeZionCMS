<div id="left">
	<ul>
		<?php /* ?><li class="active"><a href="<?php echo Router::url('backoffice/home/dashboard'); ?>"><?php echo _("Accueil"); ?></a></li><?php */ ?>		
		<li>
			<a><?php echo _("CONTENUS"); ?></a>
			<ul>
				<li><a href="<?php echo Router::url('backoffice/categories/index'); ?>"><?php echo _("Catégories"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/sliders/index'); ?>"><?php echo _("Sliders"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/focus/index'); ?>"><?php echo _("Focus"); ?></a></li>
			</ul>			
		</li>		
		<li>
			<a><?php echo _("ARTICLES"); ?></a>
			<ul>
				<li><a href="<?php echo Router::url('backoffice/posts/index'); ?>"><?php echo _("Articles"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/posts_types/index'); ?>"><?php echo _("Type d'articles"); ?></a></li>
				<li><a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>"><?php echo _("Commentaires articles"); ?></a></li>
			</ul>			
		</li>			
		<li><a href="<?php echo Router::url('backoffice/contacts/index'); ?>"><?php echo _("Contacts"); ?></a></li>	
	</ul>	
	<div id="credits">&#169; Copyright 2012 koeZion CMS</div>
</div>