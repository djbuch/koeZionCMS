<?php
///////////////////////////////
//    GESTION DES ERREURS    // 
//--> http://www.ficgs.com/Comment-montrer-les-erreur-PHP-f1805.html
//ini_set( 'magic_quotes_gpc', 0 );

//Récupération de la configuration du coeur
require_once(LIBS.DS.'config_magik.php');
$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'core.ini', true, false);
$coreConfs = $cfg->keys_values();
if(!isset($coreConfs['display_php_error'])) {
	
	//Si la données n'est pas dans la liste (Cas pour d'anciennes versions)
	$httpHost = $_SERVER["HTTP_HOST"];
	if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $displayErrors = 1; } else { $displayErrors = 0; }
	
} 
else { $displayErrors = $coreConfs['display_php_error']; }
ini_set('display_errors', $displayErrors); //Affichage ou non des erreurs

//Rapporte les erreurs d'exécution de script
//Rapporter les E_NOTICE peut vous aider à améliorer vos scripts (variables non initialisées, variables mal orthographiées..)
error_reporting(E_ALL);
ini_set('log_errors', 1); //Log des erreurs dans un fichier
$logFile = TMP.DS.'logs'.DS.'php'.DS.date('Y-m-d').'.log'; //Chemin du fichier de logs
ini_set('error_log', $logFile); //Définition du chemin du fichier de logs

///////////////////////////////////////////////////////////////////
//    MISE EN PLACE DES LIENS VERS LES DIFFERENTES LIBRAIRIES    //
require_once CAKEPHP.DS.'inflector.php'; //Classe Object permettant le respect de certaines conventions

require_once KOEZION.DS.'session.php'; //On charge le composant permettant la gestion des sessions
Session::init(); //On l'initialise

require_once LIBS.DS.'file_and_dir.php'; //Chargement du Dispatcher
require_once KOEZION.DS.'object.php'; //Classe Object
require_once KOEZION.DS.'model.php'; //Classe Model
require_once KOEZION.DS.'view.php'; //Classe View
require_once KOEZION.DS.'controller.php'; //Classe Controller
require_once KOEZION.DS.'component.php'; //Classe Component
require_once KOEZION.DS.'helper.php'; //Classe Helper

require_once KOEZION.DS.'cache.php'; //
require_once KOEZION.DS.'basics.php'; //Fichier contenant un certains nombres d'instructions utiles (debug, pr, etc)
require_once KOEZION.DS.'router.php'; //Chargement de l'object Router (Analyse des Urls)
require_once CONFIGS.DS.'configure.php'; //Classe de configuration
require_once CONFIGS.DS.'routes.php'; //Fichier contenant la liste des réécritures d'url
require_once CAKEPHP.DS.'string.php'; //Classe Object permettant la manipulation de chaîne de caractères
require_once CAKEPHP.DS.'set.php'; //Classe Object permettant des manipulations sur les tableaux
require_once CAKEPHP.DS.'sanitize.php'; //Classe Object permettant des manipulations de nettoyage
require_once KOEZION.DS.'validation.php'; //Classe Object permettant la gestion des différentes règles de validation des modèles
require_once KOEZION.DS.'request.php'; //Chargement de l'objet Request
require_once CONTROLLERS.DS.'app_controller.php'; //Classe App
require_once KOEZION.DS.'koezion_plugin.php'; //Classe Plugin
require_once KOEZION.DS.'dispatcher.php'; //Chargement du Dispatcher

/////////////////////////////////////////////
//   FICHIERS BOOTSTRAP POUR LES PLUGINS   //
$moreBootstraps = CONFIGS.DS.'plugins'.DS.'bootstrap';
if(is_dir($moreBootstraps)) {

	foreach(FileAndDir::directoryContent($moreBootstraps) as $moreBootstrap) { require_once($moreBootstraps.DS.$moreBootstrap); }
}
/////////////////////////////////////////////