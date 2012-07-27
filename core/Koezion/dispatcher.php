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
			$this->error('missing_action', 'Le controller '.$this->request->controller." n'a pas de méthode ".$action);
			die();
		}
		
		//fonction qui permet d'appeler une fonction
		//Cette fonction va permettre l'appel dynamique de la fonction (située dans le controller) demandée dans l'url
		
		
		$this->dispatchMethod($controller, $action, $this->request->params);
		
		/*call_user_func_array(
			array(
				$controller,
				$action
			),
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
	
		$file_name = strtolower($this->request->controller.'_controller'); //On récupère dans une variable le nom du controller
		$file_path = ROOT.DS.'controllers'.DS.$file_name.'.php'; //On récupère dans une variable le chemin du controller
	
		if(!file_exists($file_path)) {
			
			$this->error('missing_controller', "Le controller ".$this->request->controller." n'existe pas"." ".serialize($this->request));	
			die();		
		} //On va tester l'existence de ce fichier
		require $file_path; //Inclusion de ce fichier si il existe
	
		$controller_name = Inflector::camelize($file_name); //On transforme le nom du fichier pour récupérer le nom du controller
		$controller =  new $controller_name($this->request); //Création d'une instance du controller souhaité dans lequel on injecte la request
		return $controller;
	}	
    
/**
 * Cette fonction permet l'affichage d'une page
 *
 * @param 	integer $id 	Identifiant de la page à afficher
 * @param 	varchar $slug 	Url de la page
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 03/01/2012 by FI
 * @version 0.2 - 02/03/2012 by FI - Modification de la gestion de l'affichage des erreurs
 * @todo A reprendre quand plus de temps
 */	
	function error($action, $message) {
        
		header("Location: ".Router::url('/errors/'.$action.'/'.$message));
	}
}