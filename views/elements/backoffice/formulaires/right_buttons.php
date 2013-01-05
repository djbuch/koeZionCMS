<?php 	
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du bouton colonne de droite."));
echo $helpers['Form']->input('content', 'Contenu', array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Saisissez ici le contenu de votre bouton, Pour les images ne pas dépasser 249px de largeur."));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce bouton."));