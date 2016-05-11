<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php
			echo $helpers['Form']->input('layout', _('Layout'), array('compulsory' => true, 'type' => 'select', 'datas' => $layoutsList, 'firstElementList' => _('Sélectionnez un layout')));
			echo $helpers['Form']->input('name', _("Libellé"), array('compulsory' => true, 'tooltip' => _("Indiquez le libellé du modèle de page")));
			echo $helpers['Form']->upload_files('illustration', array('label' => _("Illustration")));		
			echo $helpers['Form']->input('description', _("Description"), array('type' => 'textarea', 'tooltip' => _("Indiquez la description du modèles de page")));
			echo $helpers['Form']->input('html', _("HTML"), array('type' => 'textarea', 'wysiswyg' => true,  'class' => 'xxlarge', 'compulsory' => true, 'tooltip' => _("Indiquez le code HTML du template")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce modèle de page")));
			?>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
	</div>
</div>