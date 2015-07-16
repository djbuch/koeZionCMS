<?php
/**
 * Cette classe est chargée d'effectuer les opérations suivantes : 
 * - Instancier un objet de type Request qui va récupérer l'url
 * - Parser cette url via l'objet Router
 * - Charger le controller souhaité
 * - Lancer l'action demandée
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

/**
 * Objet de type Request
 * 
 * @var object
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 30/01/2012 by FI  
 */	
	var $request;
    
/**
 * Constructeur de la classe
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 30/01/2012 by FI 
 * @version 0.2 - 04/03/2013 by FI - Modification du constructeur pour donner la possibilité de ne pas faire le dispatch par défaut 
 */	
	public function __construct($dispatch = true) {
        
		if($dispatch) {
		
			$this->request = new Request(); //Création d'un objet de type Request		
			$this->dispatch(); //On lance la fonction principale de la classe
		}		      
	} 
	
/**
 * Fonction chargée d'initialiser le contrôleur et de lancer la fonction demandée
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 30/01/2012 by FI 
 * @version 0.2 - 18/04/2015 by FI - Mise en place du test sur le premier caractère de la fonction pour savoir si elle est privée ou non
 */	
	public function dispatch() {
		
		Router::parse($this->request->url, $this->request); //Parsing de l'url
		$controller = $this->load_controller(); //Chargement du controller
		
		//On va tester si il y a un prefixe
		$action = $this->request->action;
		if($this->request->prefix) { $action = $this->request->prefix.'_'.$action; }
		
		$isPrivateFunction = ($action[0] == '_') ? true : false; //On vérifie si la fonction demandée n'est pas une fonction privée
		
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
			|| $isPrivateFunction
		) {
			
			$this->error('Le contrôleur '.$this->request->controller." n'a pas de méthode ".$action." dans le fichier dispatcher");			
			die();
		}
		
		//Cette fonction va permettre l'appel dynamique de la fonction (située dans le controller) demandée dans l'url				
		$this->dispatch_method($controller, $action, $this->request->params);
		
		if($controller->auto_render) { $controller->render($action); } //AUTO RENDER
	}	
	
/**
 * Cette fonction est chargée de faire appel à la fonction demandée dans le contrôleur voulu
 * Cette appel se fait de façon dynamique en fonction du nombre de paramètres passés
 * 
 * @param object 	$controller Objet de type Controller
 * @param varchar 	$action 	Action demandée
 * @param array 	$params 	Tableau de paramètres
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 30/01/2012 by FI 
 */	
	public function dispatch_method($controller, $action, $params = array()) {
				
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
 * 
 * @return 	object Objet correspondant au controller souhaité
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 30/01/2012 by FI 
 */
	public function load_controller() {
	
		$fileName 			= $this->load_controller_file();
		$controllerName 	= Inflector::camelize($fileName); //On transforme le nom du fichier pour récupérer le nom du controller	
		$controller 		= new $controllerName($this->request); //Création d'une instance du controller souhaité dans lequel on injecte la request		
		return $controller;
	}	
	
/**
 * Cette fonction va charger le fichier contenant la classe d'un controller
 *
 * @param 	varchar	$controllerToLoad Contrôleur à charger
 * @return 	varchar Nom du contrôleur souhaité
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 20/10/2013 by AB - Rajout de la gestion du dossier du plugin 
 * @version 0.3 - 18/03/2014 by FI - Allègement de la gestion du chargement du fichier du controller 
 * @version 0.4 - 12/04/2014 by FI - Suppression de _plugin dans le nom d'un controller de plugin 
 * @version 0.5 - 12/04/2014 by FI - Annulation suppression de _plugin dans le nom d'un controller de plugin car un plugin peut potentiellement avoir le même nom qu'un controller existant 
 * @version 0.6 - 16/07/2015 by FI - Mise en place des hooks controllers  
 */
	public function load_controller_file($controllerToLoad = null) {
		
		if(isset($controllerToLoad) && !empty($controllerToLoad)) { $this->request->controller = $controllerToLoad; }
		
		$controllerName = strtolower($this->request->controller.'_controller'); //On récupère dans une variable le nom du controller	
		$controllerPath = CONTROLLERS.DS.$controllerName.'.php'; //On récupère dans une variable le chemin du controller
		
		//////////////////////////////////////////////
		//   RECUPERATION DES CONNECTEURS PLUGINS   //
		//Les connecteurs sont utilisés pour la correspondance entre les plugins et les dossiers des plugins
		$pluginsConnectors = get_plugins_connectors();
		if(isset($pluginsConnectors[$this->request->controller])) {
			
			$this->request->pluginFolder = $folderPlugin = $pluginsConnectors[$this->request->controller]; //Récupération du dossier du plugin si le controller appellé est dans un connector d'un plugin
			$controllerPath = PLUGINS.DS.$folderPlugin.DS.'controllers'.DS.$controllerName.'.php';
			$controllerName = strtolower($this->request->controller.'_plugin_controller');
		}
		//////////////////////////////////////////////
		
		/////////////////////////////////////////////////////////////////////
		//    VERIFICATION SI UN HOOK EST DISPONIBLE POUR LE CONTROLEUR    //
		$controllersHooks = $this->load_hooks_files('CONTROLLERS');
		$camelizedControllerName = Inflector::camelize($controllerName);		
		if(isset($controllersHooks[$camelizedControllerName])) { $controllerPath = $controllersHooks[$camelizedControllerName]; }
	
		if(file_exists($controllerPath)) { 
			
			if(isset($folderPlugin)) {

				//On doit contrôler si le plugin est installé en allant lire le fichiers
				$pluginsList = Cache::exists_cache_file(TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS, "plugins");
				$pluginControllerToLoad = Inflector::camelize($folderPlugin);
				if(!isset($pluginsList[$pluginControllerToLoad])) {
				
					$message = "Le controller du plugin ".$this->request->controller." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé";
					$this->error($message);
					die();
				}
				
				$pluginControllerBoostrap = PLUGINS.DS.$folderPlugin.DS.'controller.php';
				if(file_exists($pluginControllerBoostrap)) { require_once($pluginControllerBoostrap); }
			}
			
			require_once($controllerPath); //Inclusion de ce fichier si il existe
			return $controllerName;
			
		} else { 

			if(isset($folderPlugin)) { $message = "Le controller du plugin ".$this->request->controller." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé"; }
			else { $message = "Le controller ".$this->request->controller." n'existe pas"." dans le fichier dispatcher"; }
			$this->error($message);
			die();	
		}
	}	
		
/**
 * Cette fonction va insérer dans le fichier de log les différentes erreurs rencontrées
 * 
 * @param varchar $message Message à insérer dans les logs
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/12/2011
 */    
	public function error($message) {
        
		require_once(LIBS.DS.'config_magik.php');
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'core.ini', true, false);
		$coreConfs = $cfg->keys_values();
		
		if($coreConfs['log_php']) {

			//Rajout le 02/04/2013
			$date = date('Y-m-d');
			$traceSql =
				date('Y-m-d H:i:s').
				"|#|".
				$message.
				"|#|".
				$this->request->url.
				"\n";
			
			FileAndDir::put(TMP.DS.'logs'.DS.'php'.DS.'e404_'.$date.'.log', $traceSql, FILE_APPEND);
		}
		
		$url = Router::url('e404');
		$url .= "?e404=".$this->request->url;
		Session::write('redirectMessage', $message);
		header("Location: ".$url);		
		die();
	}
}