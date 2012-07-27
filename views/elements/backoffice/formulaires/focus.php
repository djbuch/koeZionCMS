<?php 	
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du focus"));
echo $helpers['Form']->input('content', 'Contenu', array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Saisissez ici le contenu de votre focus, n'hésitez pas à utiliser le modèle de focus pour vous aider"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce focus"));