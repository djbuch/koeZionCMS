<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php
			echo $helpers['Form']->input('column_title', _('Titre de la colonne de droite'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite")));
			echo $helpers['Form']->input('name', _("Libellé du type d'article"), array('compulsory' => true, 'tooltip' => _("Indiquez le libellé du type d'article")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce type d'article")));
			?>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
	</div>
</div>