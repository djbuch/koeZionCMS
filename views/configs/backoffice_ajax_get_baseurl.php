<?php 
$baseUrlUrls = array(
	'img' => $baseUrl."/img/layout/".$websiteLayout."/editor/",
	'js' => $baseUrl."/js/layout/".$websiteLayout."/editor/",	
);

if(!function_exists('json_encode')) {

	//utilisation d'un package PEAR
	include(PEAR.DS.'JSON.php');
	$oJson = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	$contenuPage = $oJson->encode($baseUrlUrls);
	unset($oJson);

} else { $contenuPage = json_encode($baseUrlUrls); }
echo str_replace("\\", "", $contenuPage);
?>