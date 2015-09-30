<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php 	
			echo $helpers['Form']->input('name', _('Libellé'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du type de module")));
			echo $helpers['Form']->input('icon', _("Icône"), array('tooltip' => _("Indiquez le nom de l'icône")));
			echo $helpers['Form']->input('plugin_id', _('Plugin'), array('type' => 'select', 'datas' => $plugins, 'tooltip' => _("Indiquez le plugin auquel est rattaché de type de module"), 'firstElementList' => _("Sélectionnez, si besoin, le plugin")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce type de module")));
			?>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
	</div>
</div>