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
 * @version 0.4 - 08/01/2015 by FI - Mise en place des hooks pour les helpers 
 * @version 0.5 - 12/05/2016 by FI - On récupère les Helpers additionnels depuis le contrôleur avec $controller->helpers
 */	
	public function __construct($view, $controller) {
		
		$this->view 				= $view;
		$this->controller 			= $controller;
		$this->layout 				= $controller->layout;
		$this->vars 				= $controller->get('vars');
		$this->vars['components'] 	= $controller->components;	
		$this->params 				= $controller->params;
		$this->request 				= new stdClass();
		
		//Si on a des helpers à charger
		//Il s'agit ici de helpers commun à l'ensemble des templates backoffice et frontoffice uniquement
		//S'il s'agit de helpers spécifiques il faut mettre les fichiers dans les dossiers correspondants
		if($controller->helpers) {
						
			foreach($controller->helpers as $k => $v) {
	
				//Si c'est un tableau
				if(is_array($v)) {
				
					$helper 	= low($v['helper_name']);
					$helperPath = $v['helper_path'];
					
					require_once $helperPath.DS.$helper.'_helper.php';
					unset($this->helpers[$v['helper_name']]);
					
					$helperObjectName 							= $v['helper_name'].'Helper';	
					$this->vars['helpers'][$v['helper_name']] 	= new $helperObjectName($this);
					
				} 
				//Sinon on va chercher dans le dossier des helpers
				else {
				
					$helper = low($v);
					require_once HELPERS.DS.$helper.'_helper.php';
					unset($this->helpers[$v]);
					$helperObjectName = $v.'Helper';	
					$this->vars['helpers'][$v] = new $helperObjectName($this);
				}
			}
		}
		
		//INSERTION DES EVENTUELS HELPERS DU TEMPLATE (BACK OU FRONT)//		
		if(defined('FRONTOFFICE_VIEWS')) { $moreHelpers = FRONTOFFICE_VIEWS.DS.'helpers'; } //Cette variable n'existe qu'en front
		else { 
			
			$moreHelpers = BACKOFFICE_VIEWS.DS.'helpers';
			///$moreHelpers = HELPERS.DS.'backoffice'; 
		
		} //Backoffice
		
		if(is_dir($moreHelpers)) {
		
			foreach(FileAndDir::directoryContent($moreHelpers) as $moreHelper) {
		
				$helperPath = $moreHelpers.DS.$moreHelper;
				
				//On va effectuer un contrôle pour vérifier si un hook n'est pas en place pour le helper concerné
				if(defined('FRONTOFFICE_VIEWS')) {	
    	
			    	$websiteHooks = $this->vars['websiteParams'];    	
			    	$helpersHooks = $this->load_hooks_files('HELPERS', $websiteHooks);
			    	if(isset($helpersHooks[$moreHelper])) { $helperPath = $helpersHooks[$moreHelper]; }    	
				}
				
				require_once($helperPath);
				$helperClass = Inflector::camelize(str_replace('_helper.php', '', $moreHelper));
				$helperObjectName = $helperClass.'Helper';				
				$this->vars['helpers'][$helperClass] = new $helperObjectName($this);
			}
		}		
		
		$this->rendered = false;		
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PUBLIQUES										//
//////////////////////////////////////////////////////////////////////////////////////////////////

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
 * @version 0.5 - 07/08/2014 by FI - Mise en place des hooks pour les vues
 * @version 0.6 - 01/11/2014 by FI - Modification de la gestion des hooks, la gestion étant maintenant par site on récupère la donnée issue de la BDD et on ne charge plus tous les fichiers. Fonctionnement plus simple lors de la gestion multisites
 * @version 0.7 - 08/12/2014 by FI - Modification de la récupération de la variable $hookPathView
 * @version 0.8 - 26/08/2015 by FI - Suppression de la variable $inViewsFolder passée en paramètre et modification du chemin de la vue backoffice 
 * @version 0.9 - 17/09/2015 by FI - Rajout d'un contrôle supplémentaire sur la vue à charger pour vérifier si le fichier demandé n'existe pas dans l'arborescence 
 * @version 1.0 - 07/10/2015 by FI - Rajout d'un contrôle supplémentaire si on indique un chemin de fichier complet
 * @version 1.1 - 05/02/2016 by FI - Gestion de l'erreur lorsque la vue n'est pas dispobible
 * @version 1.2 - 12/05/2016 by FI - Affichage du message d'erreur lorsque le layout n'est pas sélectionné
 * @todo IMPORTANT essayer de voir pourquoi si on retire le file_exists($view) la fonction export du plugin formulaire ne marche plus!!!
 * @todo Essayer d'améliorer l'ajout de websitebaseurl dans le template car il est inséré juste après la récupération de la vue --> supprimé le 25/06/2013 rajouté directement dans le template
 */    
    public function render() {
    	    	
    	if($this->rendered) { return false; } //Si la vue est déjà rendue on retourne faux
    
    	extract($this->vars); //On récupère les variables		
    	
    	//AJAX : si on trouve ajax_ dans le nom de la vue par défaut on change la valeur du layout
    	if(substr($this->view, 0, 5) == 'ajax_' || substr($this->view, 0, 16) == 'backoffice_ajax_') { $this->layout = 'ajax'; }
    	
    	//Si on désire rendre une vue particulière celle
    	if(strpos($this->view, '/') === 0) { $view = VIEWS.$this->view.'.php'; }
    	
    	//Si on indique un chemin de fichier complet
    	else if($this->view != 'index' && file_exists($this->view.'.php')) { $view = $this->view.'.php'; }
    	
    	//Sinon le comportement par défaut ira chercher les vues dans le dossier views puis dans le dossier du layout correspond
    	else {    		
    		
    		//Cas des vues backoffice
    		if(defined('BACKOFFICE_VIEWS')) { $view = BACKOFFICE_VIEWS.DS.$this->controller->request->controller.DS.$this->view.'.php'; } 
    		
    		//Si la variable existe (Elle n'existe que pour le front)
    		//Redéfinition du chemin des vues en fonction du template
    		else if(isset($this->vars['websiteParams'])) {
    			
    			$templateLayout = $this->vars['websiteParams']['tpl_layout']; //On récupère le layout courant
    			//$alternativeView = VIEWS.DS.'layout_views'.DS.$templateLayout.DS.$this->controller->request->controller.DS.$this->view.'.php'; //On génère une variable contenant le chemin vers une vue alternative située dans un dossier portant le nom du layout
    			$alternativeView = FRONTOFFICE_VIEWS.DS.$this->controller->request->controller.DS.$this->view.'.php'; //On génère une variable contenant le chemin vers une vue alternative située dans un dossier portant le nom du layout
    		
	    		//Si ce fichier n'existe pas on prendra la vue par défaut
    			if(file_exists($alternativeView)) { $view = $alternativeView; }
    		}    	 
    		
    		//Autres cas
    		else { $view = $this->view.'.php'; }
    		
	    	//Cas des plugins
	    	//On adopte un comportement par défaut pour le rendu des vues des plugins
	    	//On va les chercher dans le dossier du plugin, puis dans le dossier views, puis dans le dossier du controlleur
	    	$pluginsList = $this->controller->plugins;
	    	if(isset($params['controllerName'])) {

				$potentialPluginControllerName = Inflector::camelize($params['controllerName']);

				//////////////////////////////////////////////
				//   RECUPERATION DES CONNECTEURS PLUGINS   //
				$pluginsConnectors = get_plugins_connectors();
				if (isset($pluginsConnectors[$params['controllerFileName']])) {
					$potentialPluginControllerName = Inflector::camelize($pluginsConnectors[$params['controllerFileName']]['plugin_folder']);
				}
				//////////////////////////////////////////////

				if (isset($pluginsList[$potentialPluginControllerName])) {

					$pluginInfos = $pluginsList[$potentialPluginControllerName];

					//Si la variable existe (Elle n'existe que pour le front)
					//Redéfinition du chemin des vues en fonction du template
					if (isset($this->vars['websiteParams'])) {
						$view = $alternativeView;
					} //Le travail est déjà fait plus haut
					else {
						$view = $pluginsConnectors[$params['controllerFileName']]['plugin_path'] . DS . $pluginInfos['code'] . DS . 'views' . DS . $params['controllerFileName'] . DS . $this->view . '.php';
					}
				}
			}
    	}
	
    	////////////////////////////////////////////////////////
    	//VERIFICATION SI UN HOOK EST DISPONIBLE POUR LES VUES//
    	//Ce hook permet de redéfinir à la volée le chemin de certaines vues
    	//Cela s'avère pratique dans le cas de template particulier n'ayant pas besoin de l'ensemble des fonctionnalités disponible dans la version de base
    	//
    	//La structure du fichier views.php est :
    	//
    	//	$viewsHooks = array(
    	//		'VUE_INITIALEMENT_SOUHAITEE' => 'VUE_REELLEMENT_SOUHAITEE'
    	//	);
    	//
    	//Par exemple :
    	//
    	//	$viewsHooks = array(
    	//		'products/view' => VIEWS.DS.'hooks'.DS.'products_view.php'
    	//	);
    	//
    	//La vue initialement souhaitée est de la forme nom_du_controleur/nom_de_la_vue
    	//
    	//Nous allons donc charger les fichiers hooks, s'il y en a, et effectuer des tests sur l'existence d'une ligne pour la vue courante    	
    	if(isset($this->vars['websiteParams'])) { $websiteHooks = $this->vars['websiteParams']; } //Frontoffice  
    	else { $websiteHooks = Session::read('Backoffice.Websites.details.'.CURRENT_WEBSITE_ID); } //Backoffice
    	$viewsHooks = $this->load_hooks_files('VIEWS', $websiteHooks);
    	if($this->controller->request->prefix) { $hookAction = $this->controller->request->prefix.'_'.$params['action']; }
    	else { $hookAction = $params['action']; }
    	$hookPathView = $params['controllerFileName'].'/'.$hookAction;
    	if(isset($viewsHooks[$hookPathView])) { 
    		
    		$view = $viewsHooks[$hookPathView];
    		
    		//On test l'extension 
    		$fileInfo = new SplFileInfo($view);
    		$extension = $fileInfo->getExtension(); 
    		if(!$extension) { $view .= '.php'; }
    	}
    	
    	//ANCIENNE VERSION
    	//foreach(FileAndDir::directoryContent(CONFIGS_HOOKS.DS.'views') as $hookFile) { include(CONFIGS_HOOKS.DS.'views'.DS.$hookFile); } //Chargement des fichier    	
    	//if(isset($viewsHooks[$params['controllerVarName'].'/'.$params['action']])) { $view = $viewsHooks[$params['controllerVarName'].'/'.$params['action']]; }
    	    	
    	ob_start(); //On va récupérer dans une variable le contenu de la vue pour l'affichage dans la variable layout_for_content
    	if(file_exists($view)) { require_once($view); } //Chargement de la vue
		/*else {
    		
    		Session::write('redirectMessage', "View::render : Impossible de charger la vue");
    		$this->redirect('home/e404');
    		die();
    		
    	}*/
    	$content_for_layout = ob_get_clean(); //On stocke dans cette variable le contenu de la vue   	
    	
    	///////////////////////////////////////////////////////
    	//    ON VA TESTER SI UN HOOK LAYOUT EST EN PLACE    //
    	$layoutsHooks = $this->load_hooks_files('LAYOUTS', $websiteHooks);
    	if(isset($layoutsHooks[$this->layout])) { require_once $layoutsHooks[$this->layout].'.php'; }
    	else {    	
	    	
    		$alternativeLayoutFolder = substr_count($this->layout, DS) + substr_count($this->layout, '/');
	    	
	    	if($alternativeLayoutFolder) { require_once $this->layout.'.php'; }
	    	else if(defined('FRONTOFFICE_VIEWS') && file_exists(FRONTOFFICE_VIEWS.DS.'layout'.DS.$this->layout.'.php')) { require FRONTOFFICE_VIEWS.DS.'layout'.DS.$this->layout.'.php'; } //Chemin d'un élément d'un layout
	    	else if(defined('BACKOFFICE_VIEWS') && file_exists(BACKOFFICE_VIEWS.DS.'layout'.DS.$this->layout.'.php')) { require BACKOFFICE_VIEWS.DS.'layout'.DS.$this->layout.'.php'; } //Chemin d'un élément d'un layout
	    	else if(file_exists(VIEWS.DS.'layout'.DS.$this->layout.'.php')) { require_once VIEWS.DS.'layout'.DS.$this->layout.'.php'; } //On fait l'inclusion du layout par défaut et on affiche la variable dedans
			else {
			
				$message = '<div style="background-color:#EBEBEB;border:1px dashed black;padding:10px;position:absolute;top:50%;left:50%;width:300px;height:190px;margin-left:-150px;margin-top:-95px;">';
					$message .= '<p style="text-align:justify;line-height:20px;">';
						$message .= _("VOUS N'AVEZ PAS DEFINI DE LAYOUT POUR VOTRE SITE INTERNET VEUILLEZ VOUS CONNECTER AU BACKOFFICE ET COMPLETER LES INFORMATIONS DANS LE MODULE SITES INTERNET.");
					$message .= '</p>';
					$message .= '<p style="text-align:center;">';
						$message .= '<a href="'.Router::url("/connexion").'">'._("ME CONNECTER AU BACKOFFICE").'</a>';
					$message .= '</p>';
				$message .= '</div>';
				die($message);			
			}
    	}
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
 * @version 0.6 - 01/11/2014 by FI - Modification de la gestion des hooks, la gestion étant maintenant par site on récupère la donnée issue de la BDD et on ne charge plus tous les fichiers. Fonctionnement plus simple lors de la gestion multisites
 * @version 0.7 - 21/01/2015 by FI - Réorganisation de la fonction pour une gestion plus souple des hooks éléments plugins (BO)
 * @version 0.8 - 26/08/2015 by FI - Modification du chemin des éléments backoffice
 * @version 0.9 - 14/09/2015 by FI - Modification de la gestion des vaiables complémentaires passées à l'élément, on ne globalise plus en les ajoutant à $this->vars
 * @version 1.0 - 19/01/2016 by FI - Rajout de l'interface dans le test de récupération d'un hook
 * @version 1.1 - 20/01/2016 by FI - Gestion des dossiers de stockage des plugins
 * @version 1.2 - 31/05/2016 by FI - Rajout de $extractVars
 */
    public function element($element, $vars = null, $isPlugin = false, $extractVars = false) {
    	
    	if(isset($vars) && !empty($vars)) { 
    		
    		if($extractVars) { extract($vars); }
    		else {
    			
    			foreach($vars as $k => $v) { $this->vars[$k] = $v; }
    		}
    	}    	
    	extract($this->vars);  
    					    	
    	/////////////////////
    	// CAS DES PLUGINS //
    	//On est dans le cas d'un plugin si la variable $this->controller->params['pluginFolder'] existe
    	if($isPlugin && isset($this->controller->params['pluginFolder']) && !empty($this->controller->params['pluginFolder'])) { 
    		
    		$pluginData = $this->controller->plugins[Inflector::camelize($this->controller->params['pluginFolder'])];
    		$elementHook = $this->controller->params['pluginFolder'].'/views/elements/'.$this->controller->params['controllerFileName'].'/'.$element;
    		$element = $pluginData['path'].'/'.$this->controller->params['pluginFolder'].'/views/elements/'.$this->controller->params['controllerFileName'].'/'.$element;	
    	} 
    	
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
    	//Nous allons donc charger les fichiers hooks, s'il y en a, et effectuer des tests sur l'existence d'une ligne pour l'élément courant  
    	if(isset($this->vars['websiteParams'])) { $websiteHooks = $this->vars['websiteParams']; } //Frontoffice  
    	else { $websiteHooks = Session::read('Backoffice.Websites.details.'.CURRENT_WEBSITE_ID); } //Backoffice
    	$elementsHooks = $this->load_hooks_files('ELEMENTS', $websiteHooks);
    	
    	if(isset($elementsHooks[$element])) { $element = $elementsHooks[$element]; }
    	if(isset($elementsHooks[INTERFACE_USED.'/'.$element])) { $element = $elementsHooks[INTERFACE_USED.'/'.$element]; } //On rajoute ce cas car dans le cas ou le backoffice et le frontoffice auraient deux éléments de même nom cela nous permet d'en faire la distinction
    	else if(isset($elementHook) && isset($elementsHooks[$elementHook])) { $element = $elementsHooks[$elementHook]; }
    	////////////////////////////////////////////////////////////
    	
    	$element = str_replace('/', DS, $element);    	
    	$element = $element.'.php';
    	if(file_exists($element)) { require $element; } //Cas le plus simple on donne tous le chemin de l'élément
    	else if(defined('FRONTOFFICE_VIEWS') && file_exists(FRONTOFFICE_VIEWS.DS.'elements'.DS.$element)) { require FRONTOFFICE_VIEWS.DS.'elements'.DS.$element; } //Chemin d'un élément d'un layout
    	else if(defined('BACKOFFICE_VIEWS') && file_exists(BACKOFFICE_VIEWS.DS.'elements'.DS.$element)) { require BACKOFFICE_VIEWS.DS.'elements'.DS.$element; } //Chemin d'un élément d'un layout
    	else { require VIEWS.DS.'missing_element.php'; } 
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
//Mise en place d'un contrôle de l'existance de la classe 20160107
// @todo REPRENDRE CETTE FONCTION 	
	public function loadControllerFile($controllerToLoad) {		
		
		$controllerName = Inflector::underscore($controllerToLoad);			
		$controller_path = CONTROLLERS.DS.$controllerName.'_controller.php'; //On récupère dans une variable le chemin du controller
		
		//////////////////////////////////////////////
		//   RECUPERATION DES CONNECTEURS PLUGINS   //
		//Les connecteurs sont utilisés pour la correspondance entre les plugins et les dossiers des plugins
		$pluginsConnectors = get_plugins_connectors();
				
		if(isset($pluginsConnectors[$controllerName])) {

			$this->request->pluginFolder = $sFolderPlugin = $pluginsConnectors[$controllerName]['plugin_folder']; //Récupération du dossier du plugin si le controller appellé est dans un connector d'un plugin
			$controller_path = $pluginsConnectors[$controllerName]['plugin_path'].DS.$sFolderPlugin.DS.'controllers'.DS.$controllerName.'_controller.php';
			$controller_name = strtolower($controllerName.'_plugin_controller');
		} else { $controller_name = strtolower($controllerName.'_controller'); }
		//////////////////////////////////////////////	
		
		if(file_exists($controller_path)) { 
			
			if(isset($sFolderPlugin)) {

				//On doit contrôler si le plugin est installé en allant lire le fichiers
				$pluginsList = Cache::exists_cache_file(TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS, "plugins");
				$pluginControllerToLoad = Inflector::camelize($sFolderPlugin);
				if(!isset($pluginsList[$pluginControllerToLoad])) {
					
					$message = '<div style="background-color:#EBEBEB;border:1px dashed black;padding:10px;position:absolute;top:50%;left:50%;width:300px;height:190px;margin-left:-150px;margin-top:-95px;">';
						$message .= '<p style="text-align:justify;line-height:20px;">';
							$message .= "Le controller du plugin ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé";
						$message .= '</p>';						
					$message .= '</div>';
					die($message);	
					
					/*$message = "Le controller du plugin ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé";					
					Session::write('redirectMessage', $message);
					$this->redirect('home/e404');
					die();*/
				}
				
				$pluginControllerBoostrap = PLUGINS.DS.$sFolderPlugin.DS.'controller.php';
				if(file_exists($pluginControllerBoostrap)) { require_once($pluginControllerBoostrap); }
			}
			
			if(!class_exists(Inflector::camelize($controller_name))) { require_once $controller_path; } //Inclusion de ce fichier si il existe
			return $controller_name;
			
		} else { 

			$message = '<div style="background-color:#EBEBEB;border:1px dashed black;padding:10px;position:absolute;top:50%;left:50%;width:300px;height:190px;margin-left:-150px;margin-top:-95px;">';
				$message .= '<p style="text-align:justify;line-height:20px;">';
			
					if(isset($sFolderPlugin)) { $message .= "Le controller du plugin ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé"; }
					else { $message .= "Le controller ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher"; }		
	
				$message .= '</p>';						
			$message .= '</div>';
			die($message);
			
			/*if(isset($sFolderPlugin)) { $message = "Le controller du plugin ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher ou n'est pas correctement installé"; }
			else { $message = "Le controller ".$controllerToLoad." n'existe pas"." dans le fichier dispatcher"; }						
			Session::write('redirectMessage', $message);			
			$this->redirect('home/e404');
			die();*/
		}
	}
}