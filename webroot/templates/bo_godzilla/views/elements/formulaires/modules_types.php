<?php 	
echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du type de module")));
echo $helpers['Form']->input('plugin_id', _('Plugin'), array('type' => 'select', 'datas' => $plugins, 'tooltip' => _("Indiquez le plugin auquel est rattaché de type de module"), 'firstElementList' => _("Sélectionnez, si besoin, le plugin")));
echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce type de module")));