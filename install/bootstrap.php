<?php 
///////////////////////////////////
//   DEFINITION DES CONSTANTES   //
define('DS', DIRECTORY_SEPARATOR); 								//Définition du séparateur dans le cas ou l'on est sur windows ou linux

define('ROOT', dirname(dirname(__FILE__))); 					//Chemin vers le dossier racine

define('CONFIGS', ROOT.DS.'configs'); 							//Chemin vers le dossier config
	define('CONFIGS_FILES', ROOT.DS.'configs'.DS.'files'); 		//Chemin vers les fichiers d'initialisation de la bdd
	define('CONFIGS_HOOKS', CONFIGS.DS.'hooks'); 				//Chemin vers le dossier des hooks
	define('CONFIGS_PLUGINS', ROOT.DS.'configs'.DS.'plugins'); 	//Chemin vers le dossier des plugins

define('COMPONENTS', ROOT.DS.'controllers'.DS.'components');	//Chemin vers les librairies koéZion

define('CAKEPHP', ROOT.DS.'core'.DS.'CakePhp'); 				//Chemin vers les librairies koéZion
define('KOEZION', ROOT.DS.'core'.DS.'Koezion'); 				//Chemin vers les librairies koéZion
define('LIBS', ROOT.DS.'core'.DS.'Libs'); 						//Chemin vers les librairies diverses
define('SWIFTMAILER', ROOT.DS.'core'.DS.'SwiftMailer'); 		//Chemin vers les librairies de SwiftMailer

define('MODELS', ROOT.DS.'models'); 							//Chemin vers le dossier models
define('BEHAVIORS', MODELS.DS.'behaviors');						//Chemin vers le dossier des comportements

define('TMP', ROOT.DS.'tmp'); 									//Chemin vers le dossier tmp

define('WEBROOT_FILES', ROOT.DS.'webroot'.DS.'files'); 			//Chemin vers le dossier files de webroot
define('WEBROOT_PLUGINS', ROOT.DS.'webroot'.DS.'plugins'); 			//Chemin vers le dossier plugins de webroot
define('WEBROOT_UPLOAD', ROOT.DS.'webroot'.DS.'upload'); 		//Chemin vers le dossier upload de webroot

//CONSTANTES DE LA PROCEDURE D'INSTALLATION
define('INSTALL_FILES', ROOT.DS.'install'.DS.'files'); 			//Chemin vers les fichiers de configuration
define('INSTALL_FUNCTIONS', ROOT.DS.'install'.DS.'functions'); 	//Chemin vers les fichiers contenants les fonctions
define('INSTALL_INCLUDE', ROOT.DS.'install'.DS.'include'); 		//Chemin vers les fichiers include de configuration
define('INSTALL_VALIDATE', ROOT.DS.'install'.DS.'validate'); 	//Chemin vers les fichiers de validation
	
//GESTION DES ERREURS --> http://www.ficgs.com/Comment-montrer-les-erreur-PHP-f1805.html
//ini_set( 'magic_quotes_gpc', 0 );
$logFile = TMP.DS.'logs'.DS.'php'.DS.date('Y-m-d').'.log'; //Chemin du fichier de logs
$httpHost = $_SERVER["HTTP_HOST"];
if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $displayErrors = 1; } else { $displayErrors = 0; }
ini_set('display_errors', $displayErrors); //Affichage des erreurs
//error_reporting(E_ALL); //On report toutes les erreurs ou error_reporting(E_ALL);
//Rapporte les erreurs d'exécution de script
//Rapporter les E_NOTICE peut vous aider à améliorer vos scripts
//(variables non initialisées, variables mal orthographiées..)
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('log_errors', 1); //Log des erreurs
ini_set('error_log', $logFile); //Définition du chemin du fichier de logs
//echo phpinfo();
/////////////////////////////	
	
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
	if($key !== false && $v != "install") $baseUrl .= "/".$v;
}
define('BASE_URL', $baseUrl); //Chemin relatif vers le coeur de l'application
/////////////////////////////////////////////////////////

///////////////////////////////////
//   CHARGEMENT DES LIBRAIRIES   //
require_once LIBS.DS.'file_and_dir.php'; //Chargement du Dispatcher
require_once KOEZION.DS.'cache.php'; //
require_once(ROOT.DS.'configs'.DS.'configure.php');
require_once(ROOT.DS.'core'.DS.'Koezion'.DS.'basics.php');
require_once(ROOT.DS.'core'.DS.'Koezion'.DS.'router.php');