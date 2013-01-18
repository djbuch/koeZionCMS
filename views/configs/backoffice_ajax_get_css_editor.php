<?php 
//On va récupérer les fichiers à intégrer dans l'éditeur
include(CONFIGS.DS.'layout'.DS.$websiteLayout.'.php');

if(!function_exists('json_encode')) {

	//utilisation d'un package PEAR
	include(PEAR.DS.'JSON.php');
	$oJson = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	$contenuPage = $oJson->encode($cssLayoutUrlEditor);
	unset($oJson);

} else { $contenuPage = json_encode($cssLayoutUrlEditor); }
echo str_replace("\\", "", $contenuPage);
?>