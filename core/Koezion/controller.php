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
class Controller extends Object {

	private $vars = array(); //Variables à passer à la vue - Ne sert que dans la classe

	//Indique si le contrôleur est paginé ou non
	var $pager = array(		
		'elementsPerPage' => 10, 	//Nombre d'élément par page
		'totalElements' => 0, 		//Nombre total d'élements récupérés
		'currentPage' => 0, 		//Page courante
		'totalPages' => 0, 			//Nombre total de pages
		'limit' => 0 				//Limit pour la requête
	);

	//private $rendered = false; //Permet de savoir si la vue à déjà été rendue

	public $request; //Objet de type Request - Public --> accessible en dehors de la classe
	public $auto_load_model = true;
	public $auto_render = true;
	public $modelName = '';
	
	var $components = array(
		'Email',
		'Text'
	);
	
	var $params = array(); //Liste des paramètres du controlleur (name, modelName) 
	
/**
 * Constructeur de la classe Controller
 *
 * @param 	object 	$request 		Objet de type Request
 * @param 	boolean $beforeFilter 	Indique si on doit lancer la fonction beforeFilter (utilisé lors de l'appel de la fonction request dans l'objet vue) 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 13/04/2012 by FI - Rajout du booléen $beforeFilter afin d'indiquer ou non si il faut lancer la fonction beforeFilter lors de la création de l'objet
 * @version 0.3 - 20/04/2012 by FI - Rajout dans les paramètres du nom de l'action
 * @version 0.4 - 07/09/2012 by FI - Chargement systématique du model
 */
	function __construct($request = null, $beforeFilter = true) {
		
		//Si un objet Request est passé en paramètre, on stocke la request dans l'instance de la classe
		if($request) { $this->request = $request; }		
		
		$controllerName = str_replace('PluginController', '', get_class($this)); //Nom du contrôleur
		$controllerName = str_replace('Controller', '', $controllerName); //Nom du contrôleur

		$modelName = Inflector::singularize($controllerName); //Création du nom du model		
		
		$this->loadModel($modelName);		
		//if($this->auto_load_model) { $this->loadModel($modelName); } //Si la variable de chargement automatique du model est à vrai chargement du model
		//else { $this->$modelName = new ModelStd(); }		
		
		$this->params['modelName'] = $modelName; //Affectation du nom du model		
		$this->params['controllerName'] = $controllerName; //Affectation du nom de la classe
		$this->params['controllerFileName'] = Inflector::underscore($controllerName); //Affectation du nom du fichier 
		$this->params['controllerVarName'] = Inflector::variable($controllerName); //Affectation du nom de la variable pour la factorisation de code dans le backoffice  
		if(isset($this->request->action)) { $this->params['action'] = $this->request->action; } //Affectation du nom de l'action à lancer  
				
		//Chargement des composants
		foreach($this->components as $k => $v) {
		
			$component = low($v); //Nom du fichier
			require_once COMPONENTS.DS.$component.'.php'; //Inclusion du fichier
			unset($this->components[$k]); //On supprime de la variable
			$this->components[$v] = new $v($this); //Et on insère l'objet
		}
		
		///////////////////////
		//GESTION DES PLUGINS//
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS;
		$cacheFile 		= "plugins";
		
		$pluginsList = Cache::exists_cache_file($cacheFolder, $cacheFile);
    
		if(!$pluginsList) {	
		
			$this->loadModel('Plugin');
			$activatePlugins = $this->Plugin->find(array('conditions' => array('online' => 1, 'installed' => 1)));
			foreach($activatePlugins as $k => $v) {
				
				$pluginName = Inflector::camelize($v['code']);
				$pluginsList[$pluginName] = array(
					'controllerClass' => $pluginName.'Controller',
					'pluginClass' => $pluginName.'Plugin',
					'code' => $v['code']
				);
			}
			
    		Cache::create_cache_file($cacheFolder, $cacheFile, $pluginsList);
    	}
    	
    	$this->plugins = $pluginsList;
		
		if($beforeFilter) { $this->beforeFilter(); } 
	}

/**
 * Cette fonction est appelée avant les traitements effectuées dans la fonction
 *
 */
	function beforeFilter() {
		
		$this->_plugins_before_functions('beforeFilter');
	}

/**
 * Cette fonction est appelée avant le rendu de la fonction
 *
 */
	function beforeRender() {		
		
		$this->_check_cache_configs();
		
		$this->_plugins_before_functions('beforeRender');
	}

/**
 * Cette fonction permet l'initialisation d'une ou plusieurs variables dans la vue
 * Elle fonctionne de la façon suivante
 * Soit elle prend en paramètres un couple clé / valeur
 * Soit un tableau avec une liste de couples clé / valeur
 *
 * @param mixed $key	Nom de la variable, ou tableau de variables
 * @param mixed $value	Valeur de la variable (optionnel)
 * @version 0.1 - 23/12/2011
	 */
	public function set($key, $value = null) {

		if(is_array($key)) {
			$this->vars += $key;
		} //Si il s'agit d'un tableau on le stocke directement dans la classe
		else { $this->vars[$key] = $value;
		} //Sinon on stocke cette valeur en indiquant la clé et la valeur
	}
	
	public function get($key) {	
		
		if(isset($this->$key)) return $this->$key;
	}

/**
 * Cette fonction permet de rendre une vue
 * Pour rendre une vue particulière il faut préfixer la variable $view par un /
 *
 * @param varchar $view 			Nom de la vue à rendre
 * @param boolean $inViewsFolder	Cette variable indique à l'objet View si la vue à rendre se trouve dans le dossier views 
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 24/09/2012 by FI - Rajout du boolean $inViewsFolder pour indiquer si le dossier de stockage de la vue est dans views
 */
	public function render($view, $inViewsFolder = true) {

		$this->beforeRender();

		$this->set('pager', $this->pager); //Variable de pagination
		$this->set('params', $this->params); //Variable de paramètres		
		$this->View = new View($view, $this);		
		$this->View->render($inViewsFolder);		
	}

/**
 * Cette fonction permet le chargement d'un model dans un controller
 *
 * @param varchar $name Nom du model à charger
 * @version 0.1 - 23/12/2011
 */
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
			if(isset($pluginsConnectors[Inflector::pluralize(Inflector::underscore($name))])) {
					
				$connectorModel = $pluginsConnectors[Inflector::pluralize(Inflector::underscore($name))];
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
			
			if($return) { return new $name($this->request->fullUrl); }
			else { $this->$name = new $name($this->request->fullUrl); } //Création d'un objet Model de type $name que l'on va instancier dans la classe
		}
	}
	
	/**
	 * Cette fonction permet le "déchargement" d'un model dans un controller
	 *
	 * @param varchar $name Nom du model à "décharger"
	 * @version 0.1 - 25/01/2011
	 */
	function unloadModel($name) {	
	
		//En premier lieu on test si le model n'est pas déjà instancié
		//et si il ne l'est pas on procède à son intenciation
		if(isset($this->$name)) { unset($this->$name); }
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
	
	protected function _plugins_before_functions($beforeFunction) {
				
		$prefix = isset($this->request->prefix) ? $this->request->prefix : ''; //Récupération du préfixe
		
		////////////////////////////////////////////////////////////////
		//GESTION DU CHARGEMENT DES PLUGINS ET DE LEURS INITIALISATION//
		//On va récupérer la liste des plugins actifs et charger les fichier
		if(isset($this->plugins) && !empty($this->plugins)) {
			
			foreach($this->plugins as $pluginName => $pluginInfos) {
		
				$pluginFile = PLUGINS.DS.$pluginInfos['code'].DS.'plugin.php';
				if(file_exists($pluginFile)) {
		
					require_once($pluginFile); //Chargement du fichier
					$pluginClass = new $pluginInfos['pluginClass']();
		
					if($prefix == 'backoffice') {
						
						$beforeFunction2Check = $beforeFunction.'_backoffice';
						if(method_exists($pluginClass, $beforeFunction2Check)) $pluginClass->$beforeFunction2Check($this);
		
					} else {
		
						$beforeFunction2Check = $beforeFunction.'_frontoffice';
						if(method_exists($pluginClass, $beforeFunction2Check)) $pluginClass->$beforeFunction2Check($this);
		
					}
				}
			}
		}		
	}
	
	protected function _check_cache_configs() {
		
		/////////////////////////////////////////////////
		//PARAMETRAGE DE LA GESTION EVENTUELLE DU CACHE//
		//A revoir quand plus de temps
		if(
				method_exists($this, '_init_caching') &&
				in_array($this->params['action'], array('add', 'edit', 'delete', 'statut', 'move2prev', 'move2next', 'ajax_order_by'))
		) {
		
			//Dans le cas de l'édition, de la suppression, du changement de status d'un élément on passe l'id pour l'initialisation
			//au cas ou on ait un fichier de cache pour cet élément
			$cachingParams = null;
			if(isset($this->request->params[0]) && (int)$this->request->params[0] > 0) { $cachingParams['identifier'] = $this->request->params[0]; }
		
			$this->_init_caching($cachingParams);
		}		
	}
}