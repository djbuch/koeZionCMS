<?php 	
echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du focus"));
echo $helpers['Form']->input('layout', '<i>(*)</i> Layout', array('tooltip' => "Indiquez le nom du layout du template"));
echo $helpers['Form']->input('version', '<i>(*)</i> Version', array('tooltip' => "Indiquez la version de ce layout (Basic, Extended; etc...)"));
echo $helpers['Form']->input('code', '<i>(*)</i> Code', array('tooltip' => "Indiquez la déclinaison de ce layout (Par exemple une couleur)"));
echo $helpers['Form']->upload_files('color', array('label' => "Thumb template (80x72px)"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce template"));