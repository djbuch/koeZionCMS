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
 * Helpers communs aux templates backoffice et au frontoffice sans modification particulière
 *
 * @var 	array (false par défaut)
 * @access 	public
 * @author 	KoéZionCMS
 * @version 0.1 - 21/05/2012 by FI
 * @version 0.2 - 22/12/2013 by FI - PAr défaut aucun helper commun
 */	
	var $helpers = false;	
	
/**
 * Constructeur de la classe
 *
 * @param 	varchar $view 			Nom de la vue à charger
 * @param 	object 	$controller 	Contrôleur faisant appel à la vue
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 13/06/2012 by FI
 * @version 0.2 - 05/06/2013 by FI - Mise en place du chargement des helpers template
 * @version 0.2 - 05/06/2013 by FI - Modification de la gestion des Helpers, par défaut on charge de façon distincte les helpers du backoffice et du frontoffice pour plus de souplesse dans la gestion des templates
 * @version 0.3 - 07/07/2014 by FI - Rajout de $this->request = new stdClass(); pour corriger l'erreur suivante Warning: Creating default object from empty value in /core/Koezion/view.php on line 342 
 */	
	public function __construct($view, $controller) {
		
		$this->view = $view;
		$this->controller = $controller;
		$this->layout = $controller->layout;
		$this->vars = $controller->get('vars');
		$this->vars['components'] = $controller->components;	
		$this->params = $controller->params;
		$this->request = new stdClass();
		
		//Si on a des helpers à charger
		//Il s'agit ici de helpers commun à l'ensemble des templates backoffice et frontoffice uniquement
		//S'il s'agit de helpers spécifiques il faut mettre les fichiers dans les dossiers correspondants
		if($this->helpers) {
			
			foreach($this->helpers as $k => $v) {
	
				$helper = low($v);
				require_once HELPERS.DS.$helper.'_helper.php';
				unset($this->helpers[$k]);
				$helperObjectName = $v.'Helper';	
				$this->vars['helpers'][$v] = new $helperObjectName($this);
			}
		}
		
		//INSERTION DES EVENTUELS HELPERS DU TEMPLATE (BACK OU FRONT)//		
		if(defined('LAYOUT_VIEWS')) { $moreHelpers = LAYOUT_VIEWS.DS.'helpers'; } //Cette variable n'existe qu'en front
		else { $moreHelpers = HELPERS.DS.'backoffice'; } //Backoffice
		
		if(is_dir($moreHelpers)) {
		
			foreach(FileAndDir::directoryContent($moreHelpers) as $moreHelper) {
		
				require_once($moreHelpers.DS.$moreHelper);
				$helperClass = Inflector::camelize(str_replace('_helper.php', '', $moreHelper));
				$helperObjectName = $helperClass.'Helper';				
				$this->vars['helpers'][$helperClass] = new $helperObjectName($this);
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
 * @version 0.3 - 05/01/2014 by FI - Mise en place de la récupération des vues plugins directement dans les dossiers des templates de façon automatique
 * @version 0.4 - 13/02/2014 by FI - Gestion automatique du layout lors de requêtes AJAX
 * @todo IMPORTANT essayer de voir pourquoi si on retire le file_exists($view) la fonction export du plugin formulaire ne marche plus!!!
 * @todo Essayer d'améliorer l'ajout de websitebaseurl dans le template car il est inséré juste après la récupération de la vue --> supprimé le 25/06/2013 rajouté directement dans le template
 */    
    public function render($inViewsFolder = true) {
    	
    	if($this->rendered) { return false; } //Si la vue est déjà rendue on retourne faux
    
    	extract($this->vars); //On récupère les variables		
    	
    	//AJAX : si on trouve ajax_ dans le nom de la vue par défaut on change la valeur du layout
    	if(substr($this->view, 0, 5) == 'ajax_' || substr($this->view, 0, 16) == 'backoffice_ajax_') { $this->layout = 'ajax'; }
    	
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
	    		
				//$view = PLUGINS.DS.$pluginInfos['code'].DS.'views'.DS.$params['controllerFileName'].DS.$this->view.'.php'; //Ancienne versiong    		
	    		//Si la variable existe (Elle n'existe que pour le front)
	    		//Redéfinition du chemin des vues en fonction du template
	    		if(isset($this->vars['websiteParams'])) {  $view = $alternativeView; } //Le travail est déjà fait plus haut
	    		else { $view = PLUGINS.DS.$pluginInfos['code'].DS.'views'.DS.$params['controllerFileName'].DS.$this->view.'.php'; }
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
 * @param 	varchar $element 	Elément à charger
 * @param 	array 	$vars 		Variables que l'on souhaite faire passer (en plus) à l'élément
 * @param 	boolean $isPlugin 	Cette variable indique à l'objet View si l'élément à inclure fait partie d'un plugin 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 21/05/2012 by FI - Rajout de la possibilité de passer des variables à la fonction
 * @version 0.3 - 24/09/2012 by FI - Rajout du boolean $inElementsFolder pour indiquer si le dossier de stockage de la vue est dans views
 * @version 0.4 - 17/01/2013 by FI - Modification du chemin de récupération des éléments suite à la modification du chemin de stockage des éléments des layout pour le frontoffice
 * @version 0.5 - 17/01/2013 by FI - Mise en place de hooks permettant de redéfinir le chemin des éléments à la volée (cf fichiers dans le dossier hook)
 * @version 0.6 - 05/06/2013 by FI - Correction inclusion éléments
 * @version 0.7 - 20/10/2013 by AB - Rajout de la gestion du dossier du plugin
 * @version 0.8 - 27/10/2013 by FI - Changement du nom de la variable inElementFolder par isPlugin
 * @version 0.9 - 18/12/2013 by FI - Modification de la gestion des hooks pour le chargement des fichiers
 */
    public function element($element, $vars = null, $isPlugin = false) {
	
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
    	//Nous allons donc parcourir le dossier contenant les fichiers hook pour les charger et effectuer des tests sur l'existence d'une ligne pour l'élément courant
    	foreach(FileAndDir::directoryContent(CONFIGS_HOOKS.DS.'elements') as $hookFile) { include(CONFIGS_HOOKS.DS.'elements'.DS.$hookFile); } //Chargement des fichier
    	if(isset($elementsHooks[$element])) { $element = $elementsHooks[$element]; }
    	////////////////////////////////////////////////////////////   	 	
    	
    	if(isset($vars) && !empty($vars)) { 
    		
    		foreach($vars as $k => $v) { $this->vars[$k] = $v; } 
    	}    	
    	extract($this->vars);   
    	 
    	/////////////////////
		// CAS DES PLUGINS //
    	//On est dans le cas d'un plugin si la variable $this->controller->params['pluginFolder'] existe
    	if($isPlugin && isset($this->controller->params['pluginFolder']) && !empty($this->controller->params['pluginFolder'])) {
		
    		$element = PLUGINS.'/'.$this->controller->params['pluginFolder'].'/views/elements/'.$this->controller->params['controllerFileName'].'/'.$element;
    	
		}
    	
    	$element = str_replace('/', DS, $element);    	
    	$element = $element.'.php';
    	if(file_exists($element)) { require $element; } //Cas le plus simple on donne tous le chemin de l'élément
    	//else if(isset($this->vars['websiteParams']) && file_exists(LAYOUT_VIEWS.DS.'elements'.DS.$element)) { require LAYOUT_VIEWS.DS.'elements'.DS.$element; } //Chemin d'un élément d'un layout
    	else if(defined('LAYOUT_VIEWS') && file_exists(LAYOUT_VIEWS.DS.'elements'.DS.$element)) { require LAYOUT_VIEWS.DS.'elements'.DS.$element; } //Chemin d'un élément d'un layout
    	else if(file_exists(ELEMENTS.DS.$element)) { require ELEMENTS.DS.$element; } //Cas basique
    	else { require ELEMENTS.DS.'backoffice'.DS.'missing_element.php'; } 
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
 * @version 0.3 - 18/03/2014 by FI - Modification gestion appel uniformisation avec la méthode du dispatcher rajout de la fonction loadControllerFile
 */
    public function request($controller, $action, $parameters = array()) {
    	
    	// creation objet request : sera passé au constructeur du controller : par défaut vide, sinon peut contenir le nom du dossier du plugin
    	$request = new Request();    	
    	$file_name = $this->loadControllerFile($controller);    	
    	$controller_name = Inflector::camelize($file_name); //On transforme le nom du fichier pour récupérer le nom du controller
    	$c =  new $controller_name($request, false); //Création d'une instance du controller souhaité dans lequel on injecte la request
    	//Appel de la fonction dans le contrôlleur
    	return call_user_func_array(array($c, $action), $parameters);
    }
	
	/*
	ANCIENNE VERSION AU CAS OU 	
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
    */
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
    public function backoffice_index_for_plugin($controller, $action) {
    	
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
    
/*FONCTION QUASI IDENTIQUE QUE CELLE DU DISPATCHER*/	
	public function loadControllerFile($controllerToLoad) {		
		
		$controllerName = Inflector::underscore($controllerToLoad);			
		$controller_path = CONTROLLERS.DS.$controllerName.'_controller.php'; //On récupère dans une variable le chemin du controller
		
		//////////////////////////////////////////////
		//   RECUPERATION DES CONNECTEURS PLUGINS   //
		//Les connecteurs sont utilisés pour la correspondance entre les plugins et les dossiers des plugins
		$pluginsConnectors = get_plugins_connectors();
		if(isset($pluginsConnectors[$controllerName])) {
			
			$this->request->pluginFolder = $sFolderPlugin = $pluginsConnectors[$controllerName]; //Récupération du dossier du plugin si le controller appellé est dans un connector d'un plugin
			$controller_path = PLUGINS.DS.$sFolderPlugin.DS.'controllers'.DS.$controllerName.'_controller.php';
			$controller_name = strtolower($controllerName.'_plugin_controller');
		} else { $controller_name = strtolower($controllerName.'_controller'); }
		//////////////////////////////////////////////
		
		if(file_exists($controller_path)) { 
			
			if(isset($sFolderPlugin)) {

				//On doit contrôler si le plugin est installé en allant lire le fichiers
				$pluginsList = Cache::exists_cache_file(TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS, "plugins");
				$pluginControllerToLoad = Inflector::camelize($sFolderPlugin);
				if(!isset($pluginsList[$pluginControllerToLoad])) {
				
					Session::write('redirectMessage', $message);
					$this->redirect('home/e404');
					die();
				}
				
				$pluginControllerBoostrap = PLUGINS.DS.$sFolderPlugin.DS.'controller.php';
				if(file_exists($pluginControllerBoostrap)) { require_once($pluginControllerBoostrap); }
			}
			
			require_once $controller_path; //Inclusion de ce fichier si il existe
			return $controller_name;
			
		} else { 

			if(isset($sFolderPlugin)) { $message = "Le controller du plugin ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé"; }
			else { $message = "Le controller ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher"; }
			Session::write('redirectMessage', $message);			
			$this->redirect('home/e404');
			die();
		}
	}
}