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
//$debut = microtime(true);

require_once 'constants.php';

//01/08/2012 - Rajout d'un test pour savoir si le site est correctement paramétré
//Si le fichier database n'existe pas cela veut dire que le site n'est pas correctement paramétré
//il faut donc rediriger vers le dossier d'installation
if(!file_exists(CONFIGS_FILES.DS.'database.ini')) {
	
	require_once SYSTEM.DS.'router.php'; //Chargement de l'object Router (Analyse des Urls)
	header("Location:".Router::url('/install',''));
	die();
}

require_once SYSTEM.DS.'bootstrap.php'; //Premier fichier lancé par l'application
require_once SYSTEM.DS.'crawlers.php'; //Gestion des crawlers

new Dispatcher(); //On créé une instance de Dispatcher

//if(Configure::read('debug') > 0) { Configure::write('timerExec', round(microtime(true) - $debut, 5)); }