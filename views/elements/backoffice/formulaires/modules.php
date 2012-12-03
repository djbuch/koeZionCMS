<?php 	
echo $helpers['Form']->input('modules_type_id', 'Type de module', array('type' => 'select', 'datas' => $modulesTypes, 'tooltip' => "Indiquez le type de module"));
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du module"));
//echo $helpers['Form']->input('item_title', 'Titre rubrique', array('tooltip' => "Indiquez le titre de la rubrique"));
echo $helpers['Form']->input('controller_name', '<i>(*)</i> Nom du contrôleur', array('tooltip' => "Indiquez le nom du contrôleur"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce module"));