<?php
/**
 * Cette classe est chargée d'effectuer les opérations suivantes : 
 * - Instancier un objet de type Request qui va récupérer l'url
 * - Parser cette url via l'objet Router
 * - Charger le controller souhaité
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
 */
class Dispatcher {
    
	var $request;
    
/**
 * Constructeur de la classe
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 30/01/2012 by FI 
 */	
	public function __construct() {
        
		$this->request = new Request(); //Création d'un objet de type Request		
		$this->dispatch(); //On lance la fonction principale de la classe		      
	} 
	
/**
 * Fonction chargée de faire les appels des bons fichiers
 */	
	function dispatch() {
		
		Router::parse($this->request->url, $this->request); //Parsing de l'url
		$controller = $this->loadController(); //Chargement du controller
		
		//On va tester si il y a un prefixe
		$action = $this->request->action;
		if($this->request->prefix) { $action = $this->request->prefix.'_'.$action; }
		
		//Gestion des erreurs pour savoir si l'action demandée existe
		//On va supprimer les fonctions de la classe parente avec le array_diff
		if(
			!in_array(
				$action,
				array_diff(
					get_class_methods($controller),
					get_class_methods('Controller')
				)
			)
		) {
			
			$this->error('Le contrôleur '.$this->request->controller." n'a pas de méthode ".$action." dans le fichier dispatcher");			
			die();
		}
		
		//fonction qui permet d'appeler une fonction
		//Cette fonction va permettre l'appel dynamique de la fonction (située dans le controller) demandée dans l'url
				
		$this->dispatchMethod($controller, $action, $this->request->params);		
		/*call_user_func_array(
			array($controller, $action),
			$this->request->params
		);*/
		
		if($controller->auto_render) { $controller->render($action); } //AUTO RENDER
	}	
	
	function dispatchMethod($controller, $action, $params = array()) {
				
		switch (count($params)) {
			case 0: 	$controller->{$action}(); 																break;
			case 1: 	$controller->{$action}($params[0]); 													break;
			case 2: 	$controller->{$action}($params[0], $params[1]); 										break;
			case 3: 	$controller->{$action}($params[0], $params[1], $params[2]); 							break;
			case 4: 	$controller->{$action}($params[0], $params[1], $params[2], $params[3]); 				break;
			case 5: 	$controller->{$action}($params[0], $params[1], $params[2], $params[3], $params[4]); 	break;
			default: 	call_user_func_array(array($controller, $action), $params); 							break;
		}
	}	
	
/**
 * Cette fonction va charger un controller
 * @return $name Objet correspondant au type de controller souhaité
 */
	function loadController() {
		
		$pluginControllerToLoad = $this->request->controller; //Sert pour verifier si un plugin est installé ou pas
		
		$file_name_default = strtolower($this->request->controller.'_controller'); //On récupère dans une variable le nom du controller		
		$file_path_default = CONTROLLERS.DS.$file_name_default.'.php'; //On récupère dans une variable le chemin du controller
		
		$file_name_plugin = strtolower($this->request->controller.'_plugin_controller'); //On récupère dans une variable le nom du controller
		$file_path_plugin = PLUGINS.DS.$this->request->controller.DS.'controller.php'; //On récupère dans une variable le chemin du controller pour le plugin
					
		//////////////////////////////////////////////
		//   RECUPERATION DES CONNECTEURS PLUGINS   //
		//Les connecteurs sont utilisés pour la correspondance entre les plugins et les dossiers des plugins
		$pluginsConnectors = get_plugins_connectors();
		if(isset($pluginsConnectors[$this->request->controller])) {
			
			$pluginControllerToLoad = $connectorController = $pluginsConnectors[$this->request->controller];
			$file_path_plugin = PLUGINS.DS.$connectorController.DS.'controller.php';
		}
		//////////////////////////////////////////////
	
		//Par défaut on va contrôler si le plugin existe en premier
		//Cela permet de pouvoir réécrire les fonctionnalités du CMS (et mêmes celles déjà existentes)		
		if(file_exists($file_path_plugin)) { 
			
			//On doit contrôler si le plugin est installé en allant lire le fichiers
			$pluginsList = Cache::exists_cache_file(TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS, "plugins");			
			$pluginControllerToLoad = Inflector::camelize($pluginControllerToLoad);
			
			//Si le plugin est installé
			if(isset($pluginsList[$pluginControllerToLoad])) {

				$file_name = $file_name_plugin;
				$file_path = $file_path_plugin;
				
			//Si il n'est pas installé on va vérifier si un contrôleur n'existe pas dans le dossier par défaut
			} else if(file_exists($file_path_default)) { 
			
				$file_name = $file_name_default;
				$file_path = $file_path_default;
			 
			//Dans le cas contraire on génère une erreur
			} else {

				$this->error("Le controller du plugin ".$pluginControllerToLoad." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé");
				die();				
			}		

		//On contrôle si le contrôleur existe bien dans le dossier par défaut
		} else if(file_exists($file_path_default)) { 
			
			$file_name = $file_name_default;
			$file_path = $file_path_default;
			 
		//Sinon on affiche une erreur
		} else { 

			$this->error("Le controller ".$this->request->controller." n'existe pas"." dans le fichier dispatcher");
			die();	
		}
		
		require $file_path; //Inclusion de ce fichier si il existe
	
		$controller_name = Inflector::camelize($file_name); //On transforme le nom du fichier pour récupérer le nom du controller		
		$controller =  new $controller_name($this->request); //Création d'une instance du controller souhaité dans lequel on injecte la request		
		return $controller;
	}	
    
	function error($message) {
        
		Session::write('redirectMessage', $message);
		header("Location: ".Router::url('e404'));
	}
}