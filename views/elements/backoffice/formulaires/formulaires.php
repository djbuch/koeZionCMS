<?php 	
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le nom du formulaire"));
echo $helpers['Form']->input('form_file', 'Fichier XML', array('type' => 'file', 'class' => 'input-file', 'tooltip' => "Cliquez sur le bouton pour télécharger le fichier XML du formulaire"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce formulaire"));