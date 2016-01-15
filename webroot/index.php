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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    DEFINITION DU CHEMIN ET CHARGEMENT DE LA LIBRAIRIE CONTENANT L'ENSEMBLE DES CONSTANTES DU SYSTEME    //
//14/01/2016 - Modification du chargement du fichier des constantes suite à le refonte complète du système de chargement et de stockage des fichiers
define('DS', 		DIRECTORY_SEPARATOR); 	//Définition du séparateur dans le cas ou l'on est sur windows ou linux
define('WEBROOT', 	dirname(__FILE__)); 	//Chemin vers le dossier webroot
define('ROOT', 		dirname(WEBROOT)); 		//Chemin vers le dossier racine du site

require_once(ROOT.DS.'core'.DS.'koeZion'.DS.'system'.DS.'constants.php');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

//01/08/2012 - Rajout d'un test pour savoir si le site est correctement paramétré
//Si le fichier database n'existe pas cela veut dire que le site n'est pas correctement paramétré
//il faut donc rediriger vers le dossier d'installation
if(!file_exists(CONFIGS_FILES.DS.'database.ini')) {
	
	require_once SYSTEM.DS.'router.php'; //Chargement de l'object Router (Analyse des Urls)
	header("Location:".Router::url('/install',''));
	die();
}

require_once SYSTEM.DS.'bootstrap.php'; //Premier fichier lancé par l'application

//21/08/2015 by FI - Rajout du filtrage des crawlers 
/////////////////////////////////
//    FILTRAGE DES CRAWLERS    //
/////////////////////////////////
if(defined('FILTERING_CRAWLERS') && FILTERING_CRAWLERS) {
	
	$referer 	= strtolower(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''); //Récupération du referer
	$ip 		= $_SERVER["REMOTE_ADDR"]; //Récupération de l'ip
	
	require_once(MODELS.DS.'unwanted_crawler.php'); //Chargement de la librairie
	$UwantedCrawlerModel 	= new UnwantedCrawler(); //Création d'un objet
	$uwantedCrawlers 		= $UwantedCrawlerModel->find(array('conditions' => array('online' => 1))); //Récupération des données en base
	
	//On parcours la liste des crawlers
	foreach($uwantedCrawlers as $uwantedCrawler) {
		
		//$refererCheck = stristr($referer, $uwantedCrawler['url']) != FALSE; //Contrôle sur le referer
		//$ipCheck 		= stristr($ip, $uwantedCrawler['ip']) != FALSE; //Contrôle sur l'adresse IP
		
		$uwantedCrawlerUrl 	= strtolower($uwantedCrawler['url']);
		$refererCheck 		= substr_count($referer, $uwantedCrawlerUrl); //Contrôle sur le referer
		$ipCheck 			= substr_count($ip, $uwantedCrawler['ip']); //Contrôle sur l'adresse IP

		//Si l'un ou l'autre des tests est vrai alors on affiche la page d'erreur
		if($refererCheck || $ipCheck) {
	    	
			header("HTTP/1.0 404 Not Found"); //On lance une erreur 404
	        readfile(SYSTEM.DS.'crawlers404.php'); //On charge le fichier qui affiche l'erreur
	        die; //On ne permet pas l'exécution du code situé après cette instruction
		}
	}
}

new Dispatcher(); //On créé une instance de Dispatcher
//if(Configure::read('debug') > 0) { Configure::write('timerExec', round(microtime(true) - $debut, 5)); }