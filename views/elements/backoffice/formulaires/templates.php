<?php 	
echo $helpers['Form']->input('name', 		_('Titre'), 	array('compulsory' => true, 'tooltip' => _("Indiquez le titre du focus")));
echo $helpers['Form']->input('layout', 		_('Layout'), 	array('compulsory' => true, 'tooltip' => _("Indiquez le nom du layout du template")));
echo $helpers['Form']->input('version', 	_('Version'), 	array('compulsory' => true, 'tooltip' => _("Indiquez la version de ce layout (Basic, Extended; etc...)")));
echo $helpers['Form']->input('code', 		_('Code'),		array('compulsory' => true, 'tooltip' => _("Indiquez la déclinaison de ce layout (Par exemple une couleur)")));
echo $helpers['Form']->upload_files('color', 				array('label' => _("Thumb template (80x72px)")));
echo $helpers['Form']->input('online', 		_('En ligne'), 	array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce template")));