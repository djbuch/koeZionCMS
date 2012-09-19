<?php 
///////////////////////////////////
//   DEFINITION DES CONSTANTES   //
define('DS', DIRECTORY_SEPARATOR); 								//Définition du séparateur dans le cas ou l'on est sur windows ou linux

define('ROOT', dirname(dirname(__FILE__))); 					//Chemin vers le dossier racine

define('CONFIGS', ROOT.DS.'configs'); 							//Chemin vers le dossier config
	define('CONFIGS_FILES', ROOT.DS.'configs'.DS.'files'); 		//Chemin vers les fichiers d'initialisation de la bdd
	define('CONFIGS_FORMS', ROOT.DS.'configs'.DS.'forms'); 		//Chemin vers les fichiers xml des formulaires

define('COMPONENTS', ROOT.DS.'controllers'.DS.'components');	//Chemin vers les librairies koéZion

define('CAKEPHP', ROOT.DS.'core'.DS.'CakePhp'); 				//Chemin vers les librairies koéZion
define('KOEZION', ROOT.DS.'core'.DS.'Koezion'); 				//Chemin vers les librairies koéZion
define('LIBS', ROOT.DS.'core'.DS.'Libs'); 						//Chemin vers les librairies diverses
define('SWIFTMAILER', ROOT.DS.'core'.DS.'SwiftMailer'); 		//Chemin vers les librairies de SwiftMailer

define('MODELS', ROOT.DS.'models'); 							//Chemin vers le dossier models
define('BEHAVIORS', MODELS.DS.'behaviors');						//Chemin vers le dossier des comportements

define('TMP', ROOT.DS.'tmp'); 									//Chemin vers le dossier tmp

define('WEBROOT_FILES', ROOT.DS.'webroot'.DS.'files'); 			//Chemin vers le dossier files de webroot
define('WEBROOT_UPLOAD', ROOT.DS.'webroot'.DS.'upload'); 		//Chemin vers le dossier upload de webroot

//CONSTANTES DE LA PROCEDURE D'INSTALLATION
define('INSTALL_FILES', ROOT.DS.'install'.DS.'files'); 			//Chemin vers les fichiers de configuration
define('INSTALL_FUNCTIONS', ROOT.DS.'install'.DS.'functions'); 	//Chemin vers les fichiers contenants les fonctions
define('INSTALL_INCLUDE', ROOT.DS.'install'.DS.'include'); 		//Chemin vers les fichiers include de configuration
define('INSTALL_VALIDATE', ROOT.DS.'install'.DS.'validate'); 	//Chemin vers les fichiers de validation
	
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
require_once(ROOT.DS.'configs'.DS.'configure.php');
require_once(ROOT.DS.'core'.DS.'Koezion'.DS.'basics.php');