<?php
/**
 * Classe parente de toutes les classes de l'application
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
 * @deprecated since 19/04/2015 by FI - Remplacée par la fonction load_model
 */	
	public function loadModel($name, $return = false, $databaseConfigs = null) {
		
		return $this->load_model($name, $return, $databaseConfigs);
	}

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
 * 		$externalSlider = $this->load_model('Slider', true, $databaseConfigs);
 * 		pr($externalSlider->find());
 *
 * @param varchar 	$name 				Nom du model à charger
 * @param boolean 	$return 			Indique si il faut ou non retourner l'objet
 * @param array 	$databaseConfigs 	Configuration de connexion différentes de celles par défaut
 * @return Objet ou rien
 * @access public
 * @author koéZionCMS
 * @version 0.1 - 23/12/2011 by FI
 * @version 0.2 - 18/03/2014 by FI - Reprise du chargement des modèles des plugins
 * @version 0.3 - 03/06/2014 by FI - Rajout de la variable $databaseConfigs permettant la connexion à une autre BDD
 * @version 0.4 - 08/08/2014 by FI - Modification des données envoyées au constructeur, création de la variable $modelParams
 * @version 0.5 - 16/07/2015 by FI - Mise en place des hooks modèles
 */
	public function load_model($name, $return = false, $databaseConfigs = null) {
		
		//En premier lieu on test si le model n'est pas déjà instancié
		//et si il ne l'est pas on procède à son intenciation
		if(!isset($this->$name)) {
			
			$file_path 			= '';				
			$file_name 			= Inflector::underscore($name).'.php'; //Nom du fichier à charger		
			$file_path_default 	= ROOT.DS.'models'.DS.$file_name; //Chemin vers le fichier à charger
			
			//Pour déterminer le dossier du plugin nous devons transformer le nom du model à charger
			//Etape 1 : passage au pluriel
			//Etape 2 : transformation du camelCased en _
			$pluginPluralizeName 	= Inflector::pluralize($name);		
			$pluginUnderscoreName 	= Inflector::underscore($pluginPluralizeName);
			
			//////////////////////////////////////////////
			//   RECUPERATION DES CONNECTEURS PLUGINS   //
			$pluginsConnectors = get_plugins_connectors();
			if(isset($pluginsConnectors[$pluginUnderscoreName])) {
					
				$connectorModel = $pluginsConnectors[$pluginUnderscoreName];
				$file_path_plugin = PLUGINS.DS.$connectorModel.DS.'models'.DS.$file_name;	
				
				//Chargement de l'éventuel fichier supplémentaire pour les models
				$pluginModelBoostrap = PLUGINS.DS.$connectorModel.DS.'model.php';
				if(file_exists($pluginModelBoostrap)) { require_once($pluginModelBoostrap); }
			}
			//////////////////////////////////////////////		
			
			if(isset($file_path_plugin) && file_exists($file_path_plugin)) { $file_path = $file_path_plugin; } //Si il y a un plugin on le charge par défaut		
			else if(file_exists($file_path_default)) { $file_path = $file_path_default; } //Sinon on test si le model par défaut existe
						
			/////////////////////////////////////////////////////////////////
			//    VERIFICATION SI UN HOOK EST DISPONIBLE POUR LE MODELE    //
			$modelsHooks = $this->load_hooks_files('MODELS');
			if(isset($modelsHooks[$name])) { $file_path = $modelsHooks[$name]; }
			
			//On va tester l'existence de ce fichier
			if(!file_exists($file_path)) { 				
				
				Session::write('redirectMessage', "Impossible de charger le modèle ".$name." dans le fichier controller");
				$this->redirect('home/e404');
				die();
			}
			
			require_once($file_path); //Inclusion du fichier
			
			$modelParams = null;
			if(isset($this->request)) { 
				
				$modelParams['url'] 				= $this->request->fullUrl;
				$modelParams['controller_action'] 	= isset($this->request->controller) && isset($this->request->action) ? $this->request->controller.'/'.$this->request->action : '';				 
			}
			
			if($return) { return new $name($modelParams, $databaseConfigs); }
			else { $this->$name = new $name($modelParams, $databaseConfigs); } //Création d'un objet Model de type $name que l'on va instancier dans la classe
		}
	}
	
/**
 * @deprecated since 19/04/2015 by FI - Remplacée par la fonction unload_model
 */	
	public function unloadModel($name) {
		
		$this->unload_model($name);
	}
	
/**
 * Cette fonction permet le "déchargement" d'un model dans un controller
 *
 * @param varchar $name Nom du model à "décharger"
 * @access public
 * @author koéZionCMS
 * @version 0.1 - 25/01/2011 by FI
 */
	public function unload_model($name) {	
	
		//En premier lieu on teste si le modèle est déjà instancié
		//s'il l'est on supprime l'instenciation
		if(isset($this->$name)) { unset($this->$name); }
	}
	
/**
 * Cette fonction permet de charger un composant
 * 
 * @param varchar 	$component 				Composant à charger
 * @param varchar 	$path 					Chemin du composant
 * @param object 	$componentController	Objet Controller
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 25/01/2011 by FI
 */	
	public function load_component($component, $path = null, $componentController = null) {	

		//Test pour vérifier si le composant à charder n'est pas celui d'un plugin
		//Si c'est le cas $component sera du type : 'P/ecommerce/Cart'
		$startChars = substr($component, 0, 2);
		if($startChars == 'P/') {
			
			$component 	= explode('/', $component);
			$path 		= PLUGINS.DS.$component[1].DS.'controllers'.DS.'components';
			$component 	= $component[2];
		}
		
		$componentFileName 		= Inflector::underscore($component); //Nom du fichier
		$componentObjectName 	= $component.'Component'; //Nom du fichier
		
		if(!isset($path)) { $componentPath = COMPONENTS; }
		else { $componentPath = $path; } 
		
		require_once $componentPath.DS.$componentFileName.'.php'; //Inclusion du fichier
		if(isset($componentController)) { $controller = $componentController; } else { $controller = $this; }
		
		$this->components[$component] = new $componentObjectName($controller); //Et on insère l'objet
	}		

/**
 * Cette fonction permet d'effectuer le chargement des fichiers hooks pour les vues et les éléments
 * 
 * @param 	varchar $type 			Type de hook (VIEWS or ELEMENTS)
 * @param 	array 	$websiteHooks 	Tableau contenant le nom des fichiers à charger
 * @return 	array 	Tableau contenant les hooks à mettre en place
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 01/11/2014 by FI
 * @version 0.2 - 08/01/2015 by FI - Rajout de HELPERS
 * @version 0.3 - 16/04/2015 by FI - Rajout de la gestion d'un fichier par défaut indépendant des données renseignées dans hook_filename
 * @version 0.4 - 16/07/2015 by FI - Déplacement de cette fonction de l'objet View et rajout des la gestion des hooks pour les controleurs et les modèles
 */	
	public function load_hooks_files($type, $websiteHooks = null) {
		
		//Dans le cas ou aucun fichier de hook ne soit demande on va quand même tester si le fichier default existe
		if(!isset($websiteHooks['hook_filename']) || empty($websiteHooks['hook_filename'])) { $websiteHooks['hook_filename'] = 'default'; }
		else { $websiteHooks['hook_filename'] .= ';default'; }
		
		if(!empty($websiteHooks['hook_filename'])) { 
					
			$hooks = explode(';', $websiteHooks['hook_filename']);
				
			if($type == 'LAYOUTS') 			{ $hooksPath = CONFIGS_HOOKS.DS.'layouts'.DS; }
			else if($type == 'VIEWS') 		{ $hooksPath = CONFIGS_HOOKS.DS.'views'.DS; }			
			else if($type == 'ELEMENTS') 	{ $hooksPath = CONFIGS_HOOKS.DS.'elements'.DS; }
			else if($type == 'HELPERS') 	{ $hooksPath = CONFIGS_HOOKS.DS.'helpers'.DS; }
			else if($type == 'CONTROLLERS') { $hooksPath = CONFIGS_HOOKS.DS.'controllers'.DS; }
			else if($type == 'MODELS') 		{ $hooksPath = CONFIGS_HOOKS.DS.'models'.DS; }
		
			foreach($hooks as $hook) {
				
				$hookFile = $hooksPath.$hook.'.php';
				if(file_exists($hookFile)) { include($hookFile); }				
			}
			
			if(isset($layoutsHooks)) 			{ return $layoutsHooks; }				
			else if(isset($viewsHooks)) 		{ return $viewsHooks; }
			else if(isset($elementsHooks)) 		{ return $elementsHooks; }
			else if(isset($helpersHooks)) 		{ return $helpersHooks; }			
			else if(isset($controllersHooks)) 	{ return $controllersHooks; }
			else if(isset($modelsHooks)) 		{ return $modelsHooks; }
		}
	}

/**
 * Cette fonction permet de faire une redirection de page
 *
 * @param varchar $url Url de redirection
 * @param integer $code Code HTTP
 * @param varchar $params Paramètres supplémentaires à passer à l'url
 * @param boolean $external Indique si l'url est externe au site 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 02/05/2012 - Test sur l'url pour savoir si il y a http:// dedans 
 * @version 0.3 - 06/11/2012 - Rajout de la possibilité de passer des paramètres 
 * @version 0.4 - 29/03/2014 - Déplacement de cette fonction de la classe Controller vers la classe Object
 * @version 0.5 - 24/02/2015 - Rajoute de la variable $external
 */
	public function redirect($url, $code = null, $params = null, $external = false) {
		 
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
		//Si un code est passé on l'indique dans le header
		if(isset($code)) { header("HTTP/1.0 ".$code." ".$http_codes[$code]); }

		//On contrôle que l'url de redirection ne commence pas par http
		if(!substr_count($url, 'http://') && !$external) { $url = Router::url($url); }

		//On rajoute les paramètres éventuels
		if(isset($params)) {$url .= '?'.$params; }
		header("Location: ".$url);
		
		die(); //Pour éviter que l'exécution de la fonction ne continue
	}	
}