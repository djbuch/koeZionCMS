<?php 
//On va récupérer les fichiers à intégrer dans l'éditeur
//$fileToLoad = CONFIGS.DS.'layout'.DS.$websiteLayout.'.php';
$fileToLoad = WEBROOT.DS.'templates'.DS.$websiteLayout.DS.'configs'.DS.$websiteLayout.'.php';
if(file_exists($fileToLoad)) { include($fileToLoad); } else { $cssLayoutUrlEditor = array(); }

if(!function_exists('json_encode')) {

	//utilisation d'un package PEAR
	include(PEAR.DS.'JSON.php');
	$oJson = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	$contenuPage = $oJson->encode($cssLayoutUrlEditor);
	unset($oJson);

} else { $contenuPage = json_encode($cssLayoutUrlEditor); }
echo str_replace("\\", "", $contenuPage);
?>