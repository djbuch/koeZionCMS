<div class="box box-primary">
	<div class="box-body">
		<?php 	
		echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du slider")));
		echo $helpers['Form']->input('image', _('Image'), array('type' => 'textarea', 'wysiswyg' => true, 'toolbar' => 'image', 'rows' => 5, 'cols' => 10, 'tooltip' => _("Insérez l'image à l'aide du module fournit par l'éditeur de texte, Taille 918/350px, Mode RVB, Résolution 72dpi")));
		echo $helpers['Form']->input('content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'tooltip' => _("Saisissez ici le descriptif de votre slider, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
		echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce slider")));
		?>
	</div>
	<div class="box-footer">
		<?php echo $helpers['Form']->button(); ?>
	</div>
</div>