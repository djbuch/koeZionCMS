<?php 
echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du crawler")));
echo $helpers['Form']->input('url', _('Url'), array('compulsory' => true, 'tooltip' => _("Indiquez l'url du crawler pour le filtrage par l'url")));
echo $helpers['Form']->input('ip', _('IP'), array('compulsory' => true, 'tooltip' => _("Indiquez l'adresse IP du crawler pour le filtrage par l'adresse IP")));
echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce crawler")));