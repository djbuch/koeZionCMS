<?php 	
echo $helpers['Form']->input('name', 		_('Code'), 			array('compulsory' => true, 'tooltip' => _("Indiquez le code du plugin")));
echo $helpers['Form']->input('description', _('Description'), 	array('compulsory' => true, 'tooltip' => _("Indiquez la description du plugin")));
echo $helpers['Form']->input('online', 		_('Activer'), 		array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer ce plugin")));