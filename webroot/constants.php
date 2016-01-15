<?php
/**
 * Constants
 *
 * Définition des constantes de l'application
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
 * @version		0.2 - 14/01/2016 by FI - Modification du chargement du fichier des constantes suite à le refonte complète du système de chargement et de stockage des fichiers
 */

////////////////////////////////////////////////////////////////////////////////////
//   MISE EN PLACE DES CONSTANTES VERS LES DIFFERENTS DOSSIERS (CHEMINS ASSOLUS)  //
////////////////////////////////////////////////////////////////////////////////////

	//Il est possible de réécrire les constantes du coeur de koéZion pour des besoins spécifiques comme par exemple mutualiser le coeur pour des développements en local.
	//Pour cela le système essayera de trouver en premier lieu un fichier constants.php qui devra être stocké à la racine du dossier tmp.
	//Si un tel fichier existe il sera chargé et les constantes utilisées seront celles de ce fichier.
	//Utile pour modifier le chemin vers le dossier core par exemple...
	//Par défaut le conseil du jour est de copier EXACTEMENT le code qui se trouve entre les {} du else et de ne modifier que ce dont vous avez besoin.
	//Ne surtout PAS SUPPRIMER de constante sous peine de voir votre application planter
	if(file_exists(ROOT.DS.'tmp'.DS.'constants_rewrite.php')) { require_once ROOT.DS.'tmp'.DS.'constants_rewrite.php'; }
	else {

		///////////////////////////
		//    DOSSIER WEBROOT    //
		define('CKEDITOR', 			WEBROOT.DS.'ck'.DS.'ckeditor');
		define('CKFINDER', 			WEBROOT.DS.'ck'.DS.'ckfinder');
		define('CSS', 				WEBROOT.DS.'css');
		define('FILES', 			WEBROOT.DS.'files');
		define('IMG', 				WEBROOT.DS.'img');
		define('JS', 				WEBROOT.DS.'js');
		define('WEBROOT_PLUGINS', 	WEBROOT.DS.'plugins');
		define('TEMPLATES', 		WEBROOT.DS.'templates');
		define('UPLOAD', 			WEBROOT.DS.'upload');

		///////////////////////////
		//    DOSSIER CONFIGS    //
		define('CONFIGS_FILES', 	ROOT.DS.'configs'.DS.'files'); 		//Chemin vers le dossier des .ini
		define('CONFIGS_HOOKS', 	ROOT.DS.'configs'.DS.'hooks'); 		//Chemin vers le dossier des hooks
		define('CONFIGS_PLUGINS', 	ROOT.DS.'configs'.DS.'plugins'); 	//Chemin vers le dossier des plugins

		////////////////////////
		//    DOSSIER CORE    //	
		define('CORE', 				ROOT.DS.'core'); 		//Chemin vers le coeur de l'application
			define('CAKEPHP', 		CORE.DS.'CakePhp'); 	//Chemin vers les librairies de cakePhp
			define('GETTEXT', 		CORE.DS.'GetText'); 	//Chemin vers les librairies de getText
			define('LIBS', 			CORE.DS.'Libs'); 		//Chemin vers les librairies diverses
			define('PEAR', 			CORE.DS.'Pear'); 		//Chemin vers les librairies de Pear
			define('SWIFTMAILER', 	CORE.DS.'SwiftMailer'); //Chemin vers les librairies de SwiftMailer
		
		/////////////////////////////////////////////////	
		//    CHEMIN VERS LES LIBRAIRIES DE KOEZION    //
		define('KOEZION', 					CORE.DS.'koeZion'); 			//Chemin vers les librairies de Koezion	
			define('CONFIGS', 				KOEZION.DS.'configs'); 			//Chemin vers le dossier configs
				define('CONFIGS_VERSIONS', 	CONFIGS.DS.'versions'); 		//Chemin vers le dossier des fichiers de versions			
			define('CONTROLLERS', 			KOEZION.DS.'controllers'); 		//Chemin vers le dossier des contrôleurs
				define('COMPONENTS', 		CONTROLLERS.DS.'components'); 	//Chemin vers le dossier des composants	
			define('MODELS', 				KOEZION.DS.'models'); 			//Chemin vers le dossier des modèles
				define('BEHAVIORS', 		MODELS.DS.'behaviors'); 		//Chemin vers le dossier des comportements			
			define('SYSTEM', 				KOEZION.DS.'system'); 			//Chemin vers le dossier des librairies système		
			define('VIEWS', 				KOEZION.DS.'views'); 			//Chemin vers le dossier des vues
				define('ELEMENTS', 			VIEWS.DS.'elements'); 			//Chemin vers le dossier des élements
				define('HELPERS', 			VIEWS.DS.'helpers'); 			//Chemin vers le dossier des helpers
				define('LAYOUT', 			VIEWS.DS.'layout'); 			//Chemin vers le dossier des layouts
		
		////////////////////////////////////////////////////////
		//    CHEMIN VERS LE DOSSIER D'INSTALLATION DU CMS    //
		//Remarque : une fois l'installation terminée il faut supprimer le dossier install
		define('INSTALL', 					ROOT.DS.'install'); 		//Chemin vers le dossier d'installation
			define('INSTALL_FILES', 		INSTALL.DS.'files'); 		//Chemin vers le dossier des fichiers copiés lors du processus d'installation
			define('INSTALL_FUNCTIONS', 	INSTALL.DS.'functions'); 	//Chemin vers les fichiers contenants les fonctions nécessaires au processus d'installation
			define('INSTALL_INCLUDE', 		INSTALL.DS.'include'); 		//Chemin vers les fichiers inclus lors du processus d'installation
			define('INSTALL_VALIDATE', 		INSTALL.DS.'validate'); 	//Chemin vers les fichiers de validation utilisés lors du processus d'installation
		
		///////////////////////////////////////////////////////////////////
		//    CHEMIN VERS LE DOSSIER CONTENANT LES DIFFERENTS PLUGINS    //
		define('PLUGINS', ROOT.DS.'plugins');	
			
		/////////////////////////////////////////////////////////////////////
		//    CHEMIN VERS LE DOSSIER CONTENANT LES FICHIERS TEMPORAIRES    //
		define('TMP', 			ROOT.DS.'tmp'); 	//Chemin vers le dossier temporaire
			define('CACHE', 	TMP.DS.'cache'); 	//Chemin vers le dossier contenant les fichiers de cache
			define('LOGS', 		TMP.DS.'logs'); 	//Chemin vers les fichiers de logs

		///////////////////////////////////////////////////////////////////
		//    CHEMIN VERS LE DOSSIER CONTENANT LES LIBRAIRIES ANNEXES    //
		define('VENDORS', ROOT.DS.'vendors');
	}
	
/////////////////////////////////////////////////////////
//   MISE EN PLACE DU CHEMIN RELATIF VERS LE WEBROOT   //
/////////////////////////////////////////////////////////

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
	
////////////////////////////////////////////////////////////////
//    DEFINITION DES DEUX CONSTANTES DE DECLARATION DU CMS    //
////////////////////////////////////////////////////////////////

	define('GENERATOR_META', 'koéZion CMS - CMS OPENSOURCE PHP');
	define('GENERATOR_LINK', '<p id="powered_by" style="position:absolute;width:20px;bottom:5px;right:5px;font-size:8px;margin-bottom:0;height:20px;opacity:.3;z-index:10000"><a href="http://www.koezion-cms.com" title="Propulsé par koéZionCMS - CMS opensource PHP" style="width:20px;height:20px;text-indent:-9999px;display:block;background: url('.BASE_URL.'/img/logo_koezion_mini.png) no-repeat top right transparent;padding: 0;margin: 0;border: none;">Propulsé par koéZionCMS - CMS opensource PHP</a></p>');

//////////////////////////////////////////////////////////////////////////////////////
//    RECUPERATION DES CONFIGURATIONS DU COEUR POUR TRANSFORMATION EN CONSTANTES    //
//////////////////////////////////////////////////////////////////////////////////////

	require_once(LIBS.DS.'config_magik.php');
	$cfg = new ConfigMagik(CONFIGS_FILES.DS.'core.ini', true, false);
	$coreConfs = $cfg->keys_values();
	
	foreach($coreConfs as $coreConfField => $coreConfValue) {
	
		$coreConfField = strtoupper($coreConfField);
		if(!defined($coreConfField)) { define($coreConfField, $coreConfValue); }
	}
	
	if(!defined('BACKOFFICE_TEMPLATE')) { define('BACKOFFICE_TEMPLATE', 'bo_godzilla'); }