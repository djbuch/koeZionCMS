<?php 	
echo $helpers['Form']->input('modules_type_id', 'Type de module', array('type' => 'select', 'datas' => $modulesTypes, 'tooltip' => "Indiquez le type de module"));
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du module"));
//echo $helpers['Form']->input('item_title', 'Titre rubrique', array('tooltip' => "Indiquez le titre de la rubrique"));
echo $helpers['Form']->input('controller_name', '<i>(*)</i> Nom du contrôleur', array('tooltip' => "Indiquez le nom du contrôleur"));
echo $helpers['Form']->input('action_name', "Nom de l'action", array('tooltip' => "Indiquez le nom de l'action"));
echo $helpers['Form']->input('no_display_in_menu', 'Ne pas afficher dans le menu', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour ne pas afficher ce module dans le menu"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce module"));