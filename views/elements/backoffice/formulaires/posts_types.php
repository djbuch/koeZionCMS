<?php 	
echo $helpers['Form']->input('column_title', '<i>(*)</i> Titre de la colonne de droite', array('tooltip' => "Indiquez le titre qui sera affiché dans la colonne de droite"));
echo $helpers['Form']->input('name', "<i>(*)</i> Libellé du type d'article", array('tooltip' => "Indiquez le libellé du type d'article"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce type d'article"));