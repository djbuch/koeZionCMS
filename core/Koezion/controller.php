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
	var $moreModels = false;
	
	var $components = array(
		'Email',
		'Text',
		'Website',
		'Date'
	);
	
	var $view = false;
	
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
 * @version 0.5 - 20/10/2013 by AB - Rajout de la gestion du dossier du plugin
 * @version 0.6 - 04/01/2014 by FI - Mise en place du système de chargement des models supplémentaires
 */
	function __construct($request = null, $beforeFilter = true) {
		
		//Si un objet Request est passé en paramètre, on stocke la request dans l'instance de la classe
		if($request) { $this->request = $request; }		
		
		$controllerName = str_replace('PluginController', '', get_class($this)); //Nom du contrôleur
		$controllerName = str_replace('Controller', '', $controllerName); //Nom du contrôleur
				
		//    CHARGEMENT DU MODEL COURANT AINSI QUE DES EVENTUELS MODELS SUPPLEMENTAIRES    //
		$modelName = Inflector::singularize($controllerName); //Création du nom du model		
		if($this->auto_load_model) { $this->load_model($modelName); } //Si la variable de chargement automatique du model est à vrai chargement du model
		if($this->moreModels) { 
			foreach($this->moreModels as $modelToLoad) { $this->load_model($modelToLoad); }
		}	
		
		$this->params['modelName'] = $modelName; //Affectation du nom du model		
		$this->params['controllerName'] = $controllerName; //Affectation du nom de la classe
		$this->params['controllerFileName'] = Inflector::underscore($controllerName); //Affectation du nom du fichier 
		$this->params['controllerVarName'] = Inflector::variable($controllerName); //Affectation du nom de la variable pour la factorisation de code dans le backoffice  
		if(isset($this->request->action)) { $this->params['action'] = $this->request->action; } //Affectation du nom de l'action à lancer

		// affectation du nom du dossier du plugin (s'il existe)
		if(isset($request->pluginFolder) && !empty($request->pluginFolder)){
			$this->params['pluginFolder'] = $request->pluginFolder;
			unset($request->pluginFolder);
		}
				
		//Chargement des composants
		if(isset($this->more_components)) { $this->components = am($this->components, $this->more_components); }
		foreach($this->components as $k => $v) {
		
			$this->load_component($v);
			unset($this->components[$k]);
		}
		
		///////////////////////
		//GESTION DES PLUGINS//
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS;
		$cacheFile 		= "plugins";
		
		$pluginsList = Cache::exists_cache_file($cacheFolder, $cacheFile);
    
		if(!$pluginsList) {	
		
			$this->load_model('Plugin');
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
 * @version 0.3 - 04/03/2013 by FI - Modification de la fonction de rendu pour pouvoir redéfinir la vue à rendre directement d'un plugin
 */
	public function render($view, $inViewsFolder = true) {

		if($this->view) { $view = $this->view; $inViewsFolder = false; }
		
		$this->beforeRender();

		$this->set('pager', $this->pager); //Variable de pagination
		$this->set('params', $this->params); //Variable de paramètres
		$this->View = new View($view, $this);		
		$this->View->render($inViewsFolder);		
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
	
	protected function _check_cache_configs($actions = array()) {		
		
		$defaultActions = array('add', 'edit', 'delete', 'statut', 'move2prev', 'move2next', 'ajax_order_by');
		$actions = array_merge($defaultActions, $actions);
		
		/////////////////////////////////////////////////
		//PARAMETRAGE DE LA GESTION EVENTUELLE DU CACHE//
		//A revoir quand plus de temps
		if(method_exists($this, '_init_caching') && in_array($this->params['action'], $actions)) {
		
			//Dans le cas de l'édition, de la suppression, du changement de status d'un élément on passe l'id pour l'initialisation
			//au cas ou on ait un fichier de cache pour cet élément
			$cachingParams = null;
			if(isset($this->request->params[0]) && (int)$this->request->params[0] > 0) { $cachingParams['identifier'] = $this->request->params[0]; }
		
			$this->_init_caching($cachingParams);
		}		
	}
}