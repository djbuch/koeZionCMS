<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php
			echo $helpers['Form']->input('code', _('Code du plugin'), array('compulsory' => true, 'tooltip' => _("Saisissez le code de votre plugin")));
			echo $helpers['Form']->input('name', _("Libellé du plugin"), array('compulsory' => true, 'tooltip' => _("Saisissez le libellé de votre plugin")));
			echo $helpers['Form']->input('description', _("Description du plugin"), array('compulsory' => true, 'tooltip' => _("Saisissez la description de votre plugin")));
			echo $helpers['Form']->input('author', _("Auteur"), array('compulsory' => true, 'tooltip' => _("Saisissez l'auteur du plugin"), 'value' => "koéZionCMS"));
			?>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
	</div>
</div>