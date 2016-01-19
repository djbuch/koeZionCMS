<?php
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