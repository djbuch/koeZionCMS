<?php
header("Content-type: text/css");
header('Content-disposition: inline; filename="application_css_styles.css"');

define('DS', DIRECTORY_SEPARATOR); //Définition du séparateur dans le cas ou l'on est sur windows ou linux
define('ROOT', dirname(dirname(__FILE__))); //Chemin vers le dossier webroot
define('CSS_PATH', ROOT.DS.'webroot'.DS.'css'.DS.'frontoffice'.DS.'default'); //Chemin vers le dossier webroot

require_once ROOT.DS.'configs'.DS.'configure.php'; //Librairie de fonctions
require_once ROOT.DS.'core'.DS.'Koezion'.DS.'basics.php'; //Librairie de fonctions
require_once ROOT.DS.'core'.DS.'Libs'.DS.'cssmin.php'; //Classe Object permettant de compresser le CSS

$css = directoryContent(CSS_PATH);

$html = '';
foreach($css as $v) {
	
	$cssContent = '';
	if(is_file(CSS_PATH.DS.$v)) {
		
		$cssContent .= CssMin::minify(file_get_contents(CSS_PATH.DS.$v));
		$cssContent = str_replace('../', '', $cssContent);
		
	}
	$html .= $cssContent."\n";
	
	/*$v = str_replace('/', DS, $v);
	$cssFile = CSS.DS.$v.'.css';
	$cssContent = CssMin::minify(file_get_contents($cssFile));
	$cssContent = str_replace('../', '', $cssContent);
	$html .= $cssContent."\n";*/
}

echo $html;

/*
A remplacer dans le layout
$css = array(
	'application_css_styles',
	'frontoffice/colors/'.trim($tpl).'/default',
	'frontoffice/colors/'.trim($tpl).'/body',
);
*/