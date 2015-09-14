<div class="box box-primary">
	<div class="box-body">
		<?php 	
		echo $helpers['Form']->input('modules_type_id', _('Type de module'), array('type' => 'select', 'datas' => $modulesTypes, 'tooltip' => _("Indiquez le type de module")));
		echo $helpers['Form']->input('name', _('Libellé'), array('tooltip' => _("Indiquez le titre du module"), 'compulsory' => true));
		echo $helpers['Form']->input('controller_name', _('Nom du contrôleur'), array('tooltip' => _("Indiquez le nom du contrôleur"), 'compulsory' => true));
		echo $helpers['Form']->input('action_name', _("Nom de l'action"), array('tooltip' => _("Indiquez le nom de l'action")));
		//echo $helpers['Form']->input('icon', _("Icône menu"), array('tooltip' => _("Indiquez le nom de l'icône menu")));
		echo $helpers['Form']->input('no_display_in_menu', _('Ne pas afficher dans le menu'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour ne pas afficher ce module dans le menu")));
		echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce module")));
		?>
	</div>
	<div class="box-footer">
		<?php echo $helpers['Form']->button(); ?>
	</div>
</div>