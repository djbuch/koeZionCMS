<?php 	
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du slider"));
echo $helpers['Form']->input('content', 'Contenu', array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Saisissez ici le descriptif de votre slider, n'hésitez pas à utiliser les modèles de pages pour vous aider, Pour les images ne pas dépasser 249px de largeur."));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce slider"));