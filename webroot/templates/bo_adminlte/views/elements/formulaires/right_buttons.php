<div class="box box-primary">
	<div class="box-body">
		<?php 	
		echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du bouton colonne de droite.")));
		echo $helpers['Form']->input('content',	_('Contenu'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le contenu de votre bouton, Pour les images ne pas dépasser 249px de largeur.")));
		echo $helpers['Form']->input('display_home_page', _("Afficher sur la page d'accueil"), 	array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour afficher ce bouton sur la page d'accueil.")));
		echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce bouton.")));
		?>
	</div>
	<div class="box-footer">
		<?php echo $helpers['Form']->button(); ?>
	</div>
</div>