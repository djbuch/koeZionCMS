<?php
require_once KOEZION.DS.'session.php'; //On charge le composant permettant la gestion des sessions
Session::init(); //On l'initialise

require_once KOEZION.DS.'basics.php'; //Fichier contenant un certains nombres d'instructions utiles (debug, pr, etc)
require_once KOEZION.DS.'router.php'; //Chargement de l'object Router (Analyse des Urls)
require_once CONFIGS.DS.'configure.php'; //Classe de configuration
require_once CONFIGS.DS.'routes.php'; //Fichier contenant la liste des réécritures d'url
require_once CAKEPHP.DS.'string.php'; //Classe Object permettant la manipulation de chaîne de caractères
require_once CAKEPHP.DS.'set.php'; //Classe Object permettant des manipulations sur les tableaux
require_once CAKEPHP.DS.'inflector.php'; //Classe Object permettant le respect de certaines conventions
require_once KOEZION.DS.'validation.php'; //Classe Object permettant la gestion des différentes règles de validation des modèles
require_once KOEZION.DS.'request.php'; //Chargement de l'objet Request
require_once KOEZION.DS.'object.php'; //Classe Object
require_once KOEZION.DS.'controller.php'; //Classe Controller
require_once CONTROLLERS.DS.'app_controller.php'; //Classe App
require_once KOEZION.DS.'model.php'; //Classe Model
require_once KOEZION.DS.'view.php'; //Classe View
require_once KOEZION.DS.'dispatcher.php'; //Chargement du Dispatcher