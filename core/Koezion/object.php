<?php
/**
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
class Object {	

/**
 * Cette fonction permet le chargement d'un model
 * 
 * Appel possible dans un contrôleur : 
 * 
 * 		$databaseConfigs = array(
 * 			'host' => "localhost",
 * 			'login' => "root",
 * 			'password' => "",
 * 			'database' => "koezion_madatabase",
 * 			'prefix' => "",
 * 			'socket' => "",
 * 			'port' => "",
 * 			'source' => "mysql"
 * 		);
 * 		$externalSlider = $this->loadModel('Slider', true, $databaseConfigs);
 * 		pr($externalSlider->find());
 *
 * @param varchar 	$name 				Nom du model à charger
 * @param boolean 	$return 			Indique si il faut ou non retourner l'objet
 * @param array 	$databaseConfigs 	Configuration de connexion différentes de celles par défaut
 * @return Objet ou rien
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 18/03/2014 - Reprise du chargement des modèles des plugins
 * @version 0.3 - 03/06/2014 - Rajout de la variable $databaseConfigs permettant la connexion à une autre BDD
 */
	function loadModel($name, $return = false, $databaseConfigs = null) {
		
		//En premier lieu on test si le model n'est pas déjà instancié
		//et si il ne l'est pas on procède à son intenciation
		if(!isset($this->$name)) {
			
			$file_path = '';				
			$file_name = Inflector::underscore($name).'.php'; //Nom du fichier à charger		
			$file_path_default = ROOT.DS.'models'.DS.$file_name; //Chemin vers le fichier à charger
			
			//Pour déterminer le dossier du plugin nous devons transformer le nom du model à charger
			//Etape 1 : passage au pluriel
			//Etape 2 : transformation du camelCased en _
			$pluginPluralizeName = Inflector::pluralize($name);		
			$pluginUnderscoreName = Inflector::underscore($pluginPluralizeName);
			
			//////////////////////////////////////////////
			//   RECUPERATION DES CONNECTEURS PLUGINS   //
			$pluginsConnectors = get_plugins_connectors();
			if(isset($pluginsConnectors[$pluginUnderscoreName])) {
					
				$connectorModel = $pluginsConnectors[$pluginUnderscoreName];
				$file_path_plugin = PLUGINS.DS.$connectorModel.DS.'models'.DS.$file_name;	
				
				//Cahrgement de l'éventuel fichier supplémentaire pour les models
				$pluginModelBoostrap = PLUGINS.DS.$connectorModel.DS.'model.php';
				if(file_exists($pluginModelBoostrap)) { require_once($pluginModelBoostrap); }
			}
			//////////////////////////////////////////////		
			
			if(isset($file_path_plugin) && file_exists($file_path_plugin)) { $file_path = $file_path_plugin; } //Si il y a un plugin on le charge par défaut		
			else if(file_exists($file_path_default)) { $file_path = $file_path_default; } //Sinon on test si le model par défaut existe
			
			if(!file_exists($file_path)) { 				
				
				Session::write('redirectMessage', "Impossible de charger le modèle ".$name." dans le fichier controller");
				$this->redirect('home/e404');
				die();
			} //On va tester l'existence de ce fichier
			
			require_once($file_path); //Inclusion du fichier
			
			if(isset($this->request->fullUrl)) { $url = $this->request->fullUrl; } else { $url = null; }			
			if($return) { return new $name($url, $databaseConfigs); }
			else { $this->$name = new $name($url, $databaseConfigs); } //Création d'un objet Model de type $name que l'on va instancier dans la classe
		}
	}
	
	/*
	ANCIENNE VERSION AU CAS OU
	function loadModel($name, $return = false) {
		
		//En premier lieu on test si le model n'est pas déjà instancié
		//et si il ne l'est pas on procède à son intenciation
		if(!isset($this->$name)) {
			
			$file_path = '';				
			$file_name = Inflector::underscore($name).'.php'; //Nom du fichier à charger		
			$file_path_default = ROOT.DS.'models'.DS.$file_name; //Chemin vers le fichier à charger
			
			//Pour déterminer le dossier du plugin nous devons transformer le nom du model à charger
			//Etape 1 : passage au pluriel
			//Etape 2 : transformation du camelCased en _
			$pluralizeName = Inflector::pluralize($name);		
			$underscoreName = Inflector::underscore($pluralizeName);		
			$file_path_plugin = PLUGINS.DS.$underscoreName.DS.'model.php'; //Chemin vers le fichier plugin à charger
			
			//////////////////////////////////////////////
			//   RECUPERATION DES CONNECTEURS PLUGINS   //
			$pluginsConnectors = get_plugins_connectors();
			//Inflector::pluralize(Inflector::underscore($name))
			if(isset($pluginsConnectors[$underscoreName])) {
					
				$connectorModel = $pluginsConnectors[$underscoreName];
				$file_path_plugin = PLUGINS.DS.$connectorModel.DS.'model.php';				
			}
			//////////////////////////////////////////////		
			
			if(file_exists($file_path_plugin)) { $file_path = $file_path_plugin; } //Si il y a un plugin on le charge par défaut		
			else if(file_exists($file_path_default)) { $file_path = $file_path_default; } //Sinon on test si le model par défaut existe
			
			if(!file_exists($file_path)) { 				
				
				Session::write('redirectMessage', "Impossible de charger le modèle ".$name." dans le fichier controller");
				$this->redirect('home/e404');
				die();
			} //On va tester l'existence de ce fichier
			
			require_once($file_path); //Inclusion du fichier
			
			if(isset($this->request->fullUrl)) { $url = $this->request->fullUrl; } else { $url = null; }			
			if($return) { return new $name($url); }
			else { $this->$name = new $name($url); } //Création d'un objet Model de type $name que l'on va instancier dans la classe
		}
	}*/
	
	/**
	 * Cette fonction permet le "déchargement" d'un model dans un controller
	 *
	 * @param varchar $name Nom du model à "décharger"
	 * @version 0.1 - 25/01/2011
	 */
	function unloadModel($name) {	
	
		//En premier lieu on test si le model est déjà instancié
		//et s'il l'est on supprime l'intenciation
		if(isset($this->$name)) { unset($this->$name); }
	}
	
	function load_component($component, $path = null, $componentController = null) {	

		//Test pour vérifier si le composant à charder n'est pas celui d'un plugin
		//Si c'est le cas $component sera du type : 'P/ecommerce/Cart'
		$startChars = substr($component, 0, 2);
		if($startChars == 'P/') {
			
			$component = explode('/', $component);
			$path = PLUGINS.DS.$component[1].DS.'controllers'.DS.'components';
			$component = $component[2];
		}
		
		$componentFileName = Inflector::underscore($component); //Nom du fichier
		$componentObjectName = $component.'Component'; //Nom du fichier
		if(!isset($path)) { $componentPath = COMPONENTS; }
		else { $componentPath = $path; } 
		require_once $componentPath.DS.$componentFileName.'.php'; //Inclusion du fichier
		if(isset($componentController)) { $controller = $componentController; } else { $controller = $this; }
		$this->components[$component] = new $componentObjectName($this); //Et on insère l'objet
	}	

/**
 * Cette fonction permet de faire une redirection de page
 *
 * @param varchar $url Url de redirection
 * @param boolean $extension Indique si il faut ou non mettre l'extension html
 * @param unknown_type $code
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 02/05/2012 - Test sur l'url pour savoir si il y a http:// dedans 
 * @version 0.3 - 06/11/2012 - Rajout de la possibilité de passer des paramètres 
 * @version 0.4 - 29/03/2014 - Déplacement de cette fonction de la classe Controller vers la classe Object
 */
	function redirect($url, $code = null, $params = null) {
		 
		//Code de redirection possibles
		$http_codes = array(
				100 => 'Continue',
				101 => 'Switching Protocols',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Time-out',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Large',
				415 => 'Unsupported Media Type',
				416 => 'Requested range not satisfiable',
				417 => 'Expectation Failed',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Time-out'
		);
		 
		if(isset($code)) { header("HTTP/1.0 ".$code." ".$http_codes[$code]); } //Si un code est passé on l'indique dans le header				
		if(!substr_count($url, 'http://')) { $url = Router::url($url); }

		if(isset($params)) {$url .= '?'.$params; }
		header("Location: ".$url);
		
		die(); //Pour éviter que les fonctions ne continues
	}	
}