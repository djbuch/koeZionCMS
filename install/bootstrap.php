<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    DEFINITION DU CHEMIN ET CHARGEMENT DE LA LIBRAIRIE CONTENANT L'ENSEMBLE DES CONSTANTES DU SYSTEME    //
//15/01/2016 - Quasiement identique au code que l'on peut trouver dans le fichier webroot/index.php
define('DS', 		DIRECTORY_SEPARATOR); 			//Définition du séparateur dans le cas ou l'on est sur windows ou linux
define('ROOT', 		dirname(dirname(__FILE__))); 	//Chemin vers le dossier racine du site
define('WEBROOT', 	ROOT.DS.'webroot'); 			//Chemin vers le dossier webroot

require_once WEBROOT.DS.'constants.php';

////////////////////////////////////////////////////////////////
//   CHARGEMENT DES LIBRAIRIES UTILISEES POUR L'INSTALLATION  //
require_once LIBS.DS.'file_and_dir.php';
require_once SYSTEM.DS.'cache.php';
require_once CONFIGS.DS.'configure.php';
require_once SYSTEM.DS.'basics.php';
require_once SYSTEM.DS.'router.php';