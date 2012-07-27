<div class="section">
	<div class="box">
		<div class="title"><h2><?php echo _("Gestion des routes"); ?></h2></div>
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->create(array('id' => 'ConfigRoute', 'action' => Router::url('backoffice/configs/router_liste'), 'method' => 'post'));
				//echo $helpers['Form']->input('posts_prefix_plural', _('Url liste articles'), array('tooltip' => _("url de la page listant tous les articles lors d'une recherche")));
				echo $helpers['Form']->input('posts_prefix_singular', _('Préfixe des urls articles'), array('tooltip' => _("Indiquez ici le préfixe par défaut des urls des articles qui sera utilisé lors de l'ajout d'article (blog par défaut ce qui donne des url du type www.domaine.com/<b>blog</b>/url-article.html)")));
				echo $helpers['Form']->input('backoffice_prefix', _('Préfixe backoffice'), array('tooltip' => _("Préfixe du backoffice (Il est conseillé de le changer de temps en temps)")));
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>