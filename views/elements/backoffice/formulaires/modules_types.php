<?php 	
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du type de module"));
echo $helpers['Form']->input('plugin_id', 'Plugin', array('type' => 'select', 'datas' => $plugins, 'tooltip' => "Indiquez le plugin auquel est rattaché de type de module", 'firstElementList' => "Sélectionnez, si besoin, le plugin"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce type de module"));