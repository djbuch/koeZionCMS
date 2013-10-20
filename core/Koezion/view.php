<?php
/**
 * 
 */
class View extends Object {   
    
	var $vars = array(); //Variables à passer à la vue - Ne sert que dans la classe
	var $rendered = false; //Permet de savoir si la vue à déjà été rendue	
	var $controller = false; //Contrôleur souhaitant afficher la vue
	var $view = false; //Vue à charger
	
/**
 * Tableau contenant la liste des helpers à charger
 *
 * @var 	array
 * @access 	public
 * @author 	KoéZionCMS
 * @version 0.1 - 21/05/2012 by FI
 */	
	var $helpers = array(
		'Html',
		'Form',
		'Paginator'
	);	
	
/**
 * Constructeur de la classe
 *
 * @param 	varchar $view 			Nom de la vue à charger
 * @param 	object 	$controller 	Contrôleur faisant appel à la vue
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 13/06/2012 by FI
 * @version 0.2 - 05/06/2013 by FI - Mise en place du chargement des helpers template
 */	
	function __construct($view, $controller) {
		
		$this->view = $view;
		$this->controller = $controller;
		$this->layout = $controller->layout;
		$this->vars = $controller->get('vars');
		$this->vars['components'] = $controller->components;	
		$this->params = $controller->params;
		
		foreach($this->helpers as $k => $v) {

			$helper = low($v);
			require_once HELPERS.DS.$helper.'.php';
			unset($this->helpers[$k]);
			$this->vars['helpers'][$v] = new $v($this);
		}
		
		//INSERTION DES EVENTUELS HELPERS DU TEMPLATE//
		//Cette variable n'existe qu'en front
		if(defined('LAYOUT_VIEWS')) {
			
			$moreHelpers = LAYOUT_VIEWS.DS.'helpers';
			if(is_dir($moreHelpers)) {
			
				foreach(FileAndDir::directoryContent($moreHelpers) as $moreHelper) { 
					
					require_once($moreHelpers.DS.$moreHelper);
					$helperClass = Inflector::camelize(str_replace('.php', '', $moreHelper));
					$this->vars['helpers'][$helperClass] = new $helperClass($this);
				}
			}
		}
		$this->rendered = false;		
    }    

/**
 * Cette fonction permet d'effectuer le rendu d'une page
 *
 * @param boolean $inViewsFolder Cette variable indique à l'objet View si la vue à rendre se trouve dans le dossier views 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 13/06/2012 by FI
 * @version 0.2 - 24/09/2012 by FI - Rajout du boolean $inViewsFolder pour indiquer si le dossier de stockage de la vue est dans views
 * @todo IMPORTANT essayer de voir pourquoi si on retire le file_exists($view) la fonction export du plugin formulaire ne marche plus!!!
 * @todo Essayer d'améliorer l'ajout de websitebaseurl dans le template car il est inséré juste après la récupération de la vue --> supprimé le 25/06/2013 rajouté directement dans le template
 */    
    public function render($inViewsFolder = true) {
    	
    	if($this->rendered) { return false; } //Si la vue est déjà rendue on retourne faux
    
    	extract($this->vars); //On récupère les variables	
    	
    	//Si on désire rendre une vue particulière celle
    	if(strpos($this->view, '/') === 0 && $inViewsFolder) { $view = VIEWS.$this->view.'.php'; }
    	
    	//Sinon le comportement par défaut ira chercher les vues dans le dossier views puis dans le dossier du layout correspond
    	else {    		
    		
    		if($inViewsFolder) { $view = VIEWS.DS.$this->controller->request->controller.DS.$this->view.'.php'; } //Cas des vues backoffice
    		else { $view = $this->view.'.php'; } //Cas de vues particulières
    		
    		//Si la variable existe (Elle n'existe que pour le front)
    		//Redéfinition du chemin des vues en fonction du template
    		if(isset($this->vars['websiteParams'])) {
    			
    			$templateLayout = $this->vars['websiteParams']['tpl_layout']; //On récupère le layout courant
    			//$alternativeView = VIEWS.DS.'layout_views'.DS.$templateLayout.DS.$this->controller->request->controller.DS.$this->view.'.php'; //On génère une variable contenant le chemin vers une vue alternative située dans un dossier portant le nom du layout
    			$alternativeView = LAYOUT_VIEWS.DS.$this->controller->request->controller.DS.$this->view.'.php'; //On génère une variable contenant le chemin vers une vue alternative située dans un dossier portant le nom du layout
    		
	    		//Si ce fichier n'existe pas on prendra la vue par défaut
    			if(file_exists($alternativeView)) { $view = $alternativeView; }
    		}    	 
    	
	    	//Cas des plugins
	    	//On adopte un comportement par défaut pour le rendu des vues des plugins
	    	//On va les chercher dans le dossier du plugin, puis dans le dossier views, puis dans le dossier du controlleur
	    	$pluginsList = $this->controller->plugins;
	    	$potentialPluginControllerName = Inflector::camelize($params['controllerName']);
	    	
	    	//////////////////////////////////////////////
	    	//   RECUPERATION DES CONNECTEURS PLUGINS   //
	    	$pluginsConnectors = get_plugins_connectors();
	    	if(isset($pluginsConnectors[$params['controllerFileName']])) { $potentialPluginControllerName = Inflector::camelize($pluginsConnectors[$params['controllerFileName']]); }
	    	//////////////////////////////////////////////
	    		    	
	    	if(isset($pluginsList[$potentialPluginControllerName]) && $inViewsFolder) {
	    		
	    		$pluginInfos = $pluginsList[$potentialPluginControllerName];    		    		
	    		$view = PLUGINS.DS.$pluginInfos['code'].DS.'views'.DS.$params['controllerFileName'].DS.$this->view.'.php';
	    	}	 
    	}
    	
    	ob_start(); //On va récupérer dans une variable le contenu de la vue pour l'affichage dans la variable layout_for_content
    	if(file_exists($view)) require_once($view); //Chargement de la vue
    	$content_for_layout = ob_get_clean(); //On stocke dans cette variable le contenu de la vue   	
    	
    	$alternativeLayoutFolder = substr_count($this->layout, DS) + substr_count($this->layout, '/');

    	//pr($alternativeLayoutFolder);
    	
    	if($alternativeLayoutFolder) { require_once $this->layout.'.php'; }
    	else if(defined('LAYOUT_VIEWS') && file_exists(LAYOUT_VIEWS.DS.'layout'.DS.$this->layout.'.php')) { require LAYOUT_VIEWS.DS.'layout'.DS.$this->layout.'.php'; } //Chemin d'un élément d'un layout
    	else { require_once VIEWS.DS.'layout'.DS.$this->layout.'.php'; } //On fait l'inclusion du layout par défaut et on affiche la variable dedans
    	$this->rendered = true; //On indique que la vue est rendue   	
    }
    
/**
 * Cette fonction permet de charger dans une vue une page html
 *
 * @param 	varchar $element 			Elément à charger
 * @param 	array 	$vars 				Variables que l'on souhaite faire passer (en plus) à l'élément
 * @param 	boolean $inElementsFolder 	Cette variable indique à l'objet View si l'élément à inclure à rendre se trouve dans le dossier elements 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 21/05/2012 by FI - Rajout de la possibilité de passer des variables à la fonction
 * @version 0.3 - 24/09/2012 by FI - Rajout du boolean $inElementsFolder pour indiquer si le dossier de stockage de la vue est dans views
 * @version 0.4 - 17/01/2013 by FI - Modification du chemin de récupération des éléments suite à la modification du chemin de stockage des éléments des layout pour le frontoffice
 * @version 0.5 - 17/01/2013 by FI - Mise en place de hooks permettant de redéfinir le chemin des éléments à la volée (cf fichiers dans le dossier hook)
 * @version 0.6 - 05/06/2013 by FI - Correction inclusion éléments
 * @version 0.7 - 20/10/2013 by AB - Rajout de la gestion du dossier du plugin
 */
    public function element($element, $vars = null, $inElementsFolder = true, $sPluginFolder = '') {

    	////////////////////////////////////////////////////////////
    	//VERIFICATION SI UN HOOK EST DISPONIBLE POUR LES ELEMENTS//
    	//Ce hook permet de redéfinir à la volée le chemin de certains éléments
    	//Cela s'avère pratique dans le cas de template particulier n'ayant pas besoin de l'ensemble des fonctionnalités disponible dans la version de base
    	//
    	//La structure du fichier elements.php est :
    	//
    	//	$elementsHooks = array(
    	//		'ELEMENT_INITIALEMENT_SOUHAITE' => 'ELEMENT_REELLEMENT_SOUHAITE'
    	//	);
    	//
    	//Par exemple :
    	//
    	//	$elementsHooks = array(
    	//		'backoffice/formulaires/categories' => 'backoffice/MON_DOSSIER/formulaires/categories'
    	//	);
    	//
    	//Cette ligne nous permet donc de redéfinir le chemin de récupération du formulaire d'ajout des catégories vers un nouveau chemin
    	if(file_exists(CONFIGS_HOOKS.DS.'elements.php')) {
    		
    		include(CONFIGS_HOOKS.DS.'elements.php');			
    		if(isset($elementsHooks[$element])) { $element = $elementsHooks[$element]; }    		
    	}     	
    	////////////////////////////////////////////////////////////   	
    	
    	if(isset($vars) && !empty($vars)) { 
    		
    		foreach($vars as $k => $v) { $this->vars[$k] = $v; } 
    	}    	
    	extract($this->vars);   
    	 
    	//pr($element);
    	
    	// gestion de l'insertion des elements des plugins
    	if(!empty($sPluginFolder) && strpos($element, PLUGINS) === false){
    		$element = PLUGINS.'/'.$sPluginFolder.'/views/elements/'.$element;
    	}
    	
    	$element = str_replace('/', DS, $element);
    	//if($element[0] != DS) { $element = ELEMENTS.DS.$element; }
    	//$element .= '.php'; //On rajoute l'extension
    	
    	//pr($element);    	   	
    	/*if($inElementsFolder) { 
    		
    		//Si la variable existe (Elle n'existe que pour le front)
    		//Redéfinition du chemin des éléments en fonction du template
    		if(isset($this->vars['websiteParams'])) { $element = ELEMENTS.DS.'layout'.DS.$element.'.php'; }
    		else { $element = ELEMENTS.DS.$element.'.php'; }   		
    		
    	} else { $element = $element.'.php'; }*/
    	
    	
    	//pr($element);
    	
    	$element = $element.'.php';
    	if(file_exists($element)) { require $element; } //Cas le plus simple on donne tous le chemin de l'élément
    	//else if(isset($this->vars['websiteParams']) && file_exists(LAYOUT_VIEWS.DS.'elements'.DS.$element)) { require LAYOUT_VIEWS.DS.'elements'.DS.$element; } //Chemin d'un élément d'un layout
    	else if(defined('LAYOUT_VIEWS') && file_exists(LAYOUT_VIEWS.DS.'elements'.DS.$element)) { require LAYOUT_VIEWS.DS.'elements'.DS.$element; } //Chemin d'un élément d'un layout
    	else if(file_exists(ELEMENTS.DS.$element)) { require ELEMENTS.DS.$element; } //Cas basique
    	else { require ELEMENTS.DS.'backoffice'.DS.'missing_element.php'; } 
    	
    	/*
    	if(!file_exists($element)) { require ELEMENTS.DS.'backoffice'.DS.'missing_element.php'; } //Si le fichier n'existe pas on affiche un message d'erreur 
    	else { require $element; } //Sinon on le charge*/
    }
    
/**
 * Cette fonction permet l'appel d'une action d'un controller depuis une vue
 *
 * @param 	varchar $controller Nom du controller à appeler
 * @param 	varchar $action 	Nom de l'action à effectuer
 * @param 	array 	$parameters Paramètres de la fonction
 * @return 	mixed 	Résultat de la fonction
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 20/10/2013 by AB - Rajout de la gestion du dossier du plugin
 */
    function request($controller, $action, $parameters = array()) {
    	
    	// creation objet request : sera passé au constructeur du controller : par défaut vide, sinon peut contenir le nom du dossier du plugin
    	$request = new Request();
    	
    	// $sFolderPlugin : récupération du nom du dossier du plugin (et aussi le nom du répertoire contenant le plugin - avec ou sans connectors)
    	
    	$sFolderPlugin = $fileNameBase = strtolower(Inflector::underscore($controller));
    	
    	$file_name_default = strtolower($fileNameBase.'_controller'); //On récupère dans une variable le nom du controller    	
    	$file_path_default = CONTROLLERS.DS.$file_name_default.'.php'; //On récupère dans une variable le chemin du controller
    	
    	$file_name_plugin = strtolower($fileNameBase.'_plugin_controller'); //On récupère dans une variable le nom du controller
    	$file_path_plugin = PLUGINS.DS.$controller.DS.'controller.php'; //On récupère dans une variable le chemin du controller
    	    	
    	//////////////////////////////////////////////
    	//   RECUPERATION DES CONNECTEURS PLUGINS   //
    	$pluginsConnectors = get_plugins_connectors();
    	if(isset($pluginsConnectors[$fileNameBase])) {
    	
    		$sFolderPlugin = $connectorController = $pluginsConnectors[$fileNameBase];
    		$file_path_plugin = PLUGINS.DS.$connectorController.DS.'controller.php';
    	}
    	//////////////////////////////////////////////    	
    	if(file_exists($file_path_plugin)) { 

    		$file_path = $file_path_plugin; $file_name = $file_name_plugin; 
    		
    		// ajout dans le request du nom du dossiers du plugins : soit le nom du controller , soit le nom du controllers connectors
    		$request->pluginFolder = $sFolderPlugin;
    		
    	} //Sinon on teste si il y a un plugin
    	else if(file_exists($file_path_default)) { $file_path = $file_path_default; $file_name = $file_name_default; } //Si le controller par défaut existe
    	    	
    	//$fileName = strtolower(Inflector::underscore($controller).'_controller'); //On récupère dans une variable le nom du controller
    	//$filePath = CONTROLLERS.DS.$fileName.'.php'; //On récupère dans une variable le chemin du controller
    	require_once $file_path; //Inclusion de ce fichier si il existe
    	$controllerName = Inflector::camelize($file_name); //On transforme le nom du fichier pour récupérer le nom du controller
    	$c = new $controllerName($request, false); //Création d'une instance du controller souhaité
    	
    	//Appel de la fonction dans le contrôlleur
    	return call_user_func_array(array($c, $action), $parameters);
    }
    
/**
 * Cette fonction permet de tester l'existence d'une fonction dans un controler depuis une vue
 * Utilisée surtout pour les plugins
 *
 * @param 	varchar $controller Nom du controller à appeler
 * @param 	varchar $action 	Nom de l'action à effectuer
 * @return 	boolean Résultat de la fonction
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 01/10/2012 by FI
 */
    function backoffice_index_for_plugin($controller, $action) {
    	
    	$file_name_default = strtolower(Inflector::underscore($controller).'_controller'); //On récupère dans une variable le nom du controller    	
    	$file_path_default = CONTROLLERS.DS.$file_name_default.'.php'; //On récupère dans une variable le chemin du controller
    	
    	$file_name_plugin = strtolower(Inflector::underscore($controller).'_plugin_controller'); //On récupère dans une variable le nom du controller
    	$file_path_plugin = PLUGINS.DS.$controller.DS.'controller.php'; //On récupère dans une variable le chemin du controller
    	
    	if(file_exists($file_path_plugin)) { $file_path = $file_path_plugin; $file_name = $file_name_plugin; } //Sinon on teste si il y a un plugin
    	else if(file_exists($file_path_default)) { $file_path = $file_path_default; $file_name = $file_name_default; } //Si le controller par défaut existe
    	else {
    	
    		Session::write('redirectMessage', "Impossible de charger le controller ".$controller." dans le fichier view");
    		$this->redirect('home/e404');
    		die();
    	} //On va tester l'existence de ce fichier
    	
    	require_once $file_path; //Inclusion de ce fichier si il existe
    	$controllerName = Inflector::camelize($file_name); //On transforme le nom du fichier pour récupérer le nom du controller
    	$c = new $controllerName(null, false); //Création d'une instance du controller souhaité
    	
    	if(!isset($c->index_view_for_backoffice)) { return true; }
    	else { return $c->index_view_for_backoffice; }
    }
}