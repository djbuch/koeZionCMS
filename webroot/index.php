<?php
/**
 * Index
 *
 * Cette page est le point d'entrée de l'application
 * Elle est chargée d'initialiser les variables globales utilisées tout au long des développements
 *
 * PHP versions 4 and 5
 *
 * KoéZionCMS : PHP OPENSOURCE CMS (http://www.koezion-cms.com)
 * Copyright KoéZionCMS
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	KoéZionCMS
 * @link        http://www.koezion-cms.com
 * @version		0.1
 */

/**
 * dirname --> permet de récupérer le dossier d'un chemin
 * __FILE__ --> constante de PHP qui contient l'url du fichier 
 */
//$debut = microtime(true);

/////////////////////////////
//   GESTION DES ERREURS   //
//http://www.ficgs.com/Comment-montrer-les-erreur-PHP-f1805.html
//ini_set( 'magic_quotes_gpc', 0 );
$logFile = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'error.log'; //Chemin du fichier de logs
ini_set('display_errors', 1); //Affichage des erreurs
ini_set('error_reporting', E_ALL); //On report toutes les erreurs ou error_reporting(E_ALL);
ini_set('log_errors', 1); //Log des erreurs
ini_set('error_log', $logFile); //Définition du chemin du fichier de logs
//echo phpinfo();
///////////////////////////// 

define('GENERATOR_META', 'koeZion CMS 2.0');
define('GENERATOR_LINK', '<p id="powered_by" style="position:absolute;width:80px;bottom:5px;right:5px;font-size:8px;margin-bottom:0;height:15px"><a href="http://www.koezion-cms.com" title="koéZion - CMS opensource" style="background:url(./img/frontoffice/mini_logo.gif) no-repeat top right transparent;width:80px;height:15px;text-indent:-9999px;display:block">propulsé par koéZionCMS</a></p>');

define('DS', DIRECTORY_SEPARATOR); //Définition du séparateur dans le cas ou l'on est sur windows ou linux

////////////////////////////////////////////////////////////////////////////////////
//   MISE EN PLACE DES CONSTANTES VERS LES DIFFERENTS DOSSIERS (CHEMINS ASSOLUS)  //
define('WEBROOT', dirname(__FILE__)); //Chemin vers le dossier webroot
	define('CSS', WEBROOT.DS.'css'); //Chemin vers le dossier css
	define('FILES', WEBROOT.DS.'files'); //Chemin vers le dossier files
		define('SEARCH_LUCENE', FILES.DS.'search'); //Chemin vers le dossier search	
	define('IMG', WEBROOT.DS.'img'); //Chemin vers le dossier img
	define('JS', WEBROOT.DS.'js'); //Chemin vers le dossier js
	define('UPLOAD', WEBROOT.DS.'upload'); //Chemin vers le dossier upload

define('ROOT', dirname(WEBROOT)); //Chemin vers le dossier racine du site

define('CONFIGS', ROOT.DS.'configs'); //Chemin vers le dossier config

define('CONTROLLERS', ROOT.DS.'controllers'); //Chemin vers le dossier des contrôleurs
	define('COMPONENTS', CONTROLLERS.DS.'components'); //Chemin vers le dossier des composants

define('CORE', ROOT.DS.'core'); //Chemin vers le coeur de l'application
	define('CAKEPHP', CORE.DS.'CakePhp'); //Chemin vers les librairies de cakePhp
	define('GETTEXT', CORE.DS.'GetText'); //Chemin vers les librairies de getText
	define('KOEZION', CORE.DS.'Koezion'); //Chemin vers les librairies de Koezion
	define('LIBS', CORE.DS.'Libs'); //Chemin vers les librairies diverses
	define('PEAR', CORE.DS.'Pear'); //Chemin vers les librairies de Pear
	define('SWIFTMAILER', CORE.DS.'SwiftMailer'); //Chemin vers les librairies de SwiftMailer
	define('ZEND', CORE.DS.'Zend'); //Chemin vers les librairies de Zend

define('LOCALE', ROOT.DS.'locale'); //Chemin vers le dossier des traductions

define('MODELS', ROOT.DS.'models'); //Chemin vers le dossier des modèles
	define('BEHAVIORS', MODELS.DS.'behaviors'); //Chemin vers le dossier des comportements
	
define('TMP', ROOT.DS.'tmp'); //Chemin vers le dossier temporaire

define('VIEWS', ROOT.DS.'views'); //Chemin vers le dossier des vues
	define('ELEMENTS', VIEWS.DS.'elements'); //Chemin vers le dossier des élements
	define('HELPERS', VIEWS.DS.'helpers'); //Chemin vers le dossier des helpers
////////////////////////////////////////////////////////////////////////////////////	
	
/////////////////////////////////////////////////////////
//   MISE EN PLACE DU CHEMIN RELATIF VERS LE WEBROOT   //
//http://www.siteduzero.com/forum-83-692076-p1-base-url.html
//define('BASE_URL', dirname(dirname($_SERVER['SCRIPT_NAME']))); //Chemin relatif vers le coeur de l'application --> OLD
//Ne marche plus si le site est dans plus d'un sous dossier
//24/07/2012 --> Correctif : maintenant tout fonctionne même si plus d'un sous dossier, correction de la génération de la variable $baseUrl
$baseUrl = '';
$scriptPath = preg_split("#[\\\\/]#", dirname(__FILE__), -1, PREG_SPLIT_NO_EMPTY);
$urlPath = preg_split("#[\\\\/]#", $_SERVER['REQUEST_URI'], -1, PREG_SPLIT_NO_EMPTY);

foreach($urlPath as $k => $v) {
	
	$key = array_search($v, $scriptPath);
	if($key !== false) $baseUrl .= "/".$v;
}
define('BASE_URL', $baseUrl); //Chemin relatif vers le coeur de l'application
/////////////////////////////////////////////////////////

require_once KOEZION.DS.'bootstrap.php'; //Premier fichier lancé par l'application

//01/08/2012 - Rajout d'un test pour savoir si le site est correctement paramétré
//Si le fichier database n'existe pas cela veut dire que le site n'est pas correctement paramétré
//il faut donc rediriger vers le dossier d'installation
if(!file_exists(CONFIGS.DS.'files'.DS.'database.ini')) {

	header("Location: ".Router::url('/install', ''));
	die();
}

new Dispatcher(); //On créé une instance de Dispatcher
//if(Configure::read('debug') > 0) { Configure::write('timerExec', round(microtime(true) - $debut, 5)); }