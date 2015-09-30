<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php
			echo $helpers['Form']->input('name', _('Libellé'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du focus")));
			echo $helpers['Form']->input('content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le contenu de votre focus, n'hésitez pas à utiliser le modèle de focus pour vous aider")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce focus")));
			?>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
	</div>
</div>