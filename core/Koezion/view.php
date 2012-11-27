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
 */    
    public function render($inViewsFolder = true) {
    	
    	if($this->rendered) { return false; } //Si la vue est déjà rendue on retourne faux
    
    	extract($this->vars); //On récupère les variables	
    	
    	//Si on désire rendre une vue particulière celle
    	if(strpos($this->view, '/') === 0 && $inViewsFolder) { $view = VIEWS.$this->view.'.php'; }
    	
    	//Sinon le comportement par défaut ira chercher les vues dans le dossier views
    	else {    		
    		
    		if($inViewsFolder) { $view = VIEWS.DS.$this->controller->request->controller.DS.$this->view.'.php'; /*pr($this);*/ }
    		else { $view = $this->view.'.php'; /*pr($this);*/ }
    		
    		//Si la variable existe (Elle n'existe que pour le front)
    		if(isset($this->vars['websiteParams'])) {
    			
    			$templateLayout = $this->vars['websiteParams']['tpl_layout']; //On récupère le layout courant
    			$alternativeView = VIEWS.DS.$this->controller->request->controller.DS.$templateLayout.DS.$this->view.'.php'; //On génère une variable contenant le chemin vers une vue alternative située dans un dossier portant le nom du layout
    		
	    		//Si ce fichier n'existe pas on prendra la vue par défaut
    			if(file_exists($alternativeView)) { $view = $alternativeView; }
    		}    		 
    	} 
    	    	
    	ob_start(); //On va récupérer dans une variable le contenu de la vue pour l'affichage dans la variable layout_for_content
    	if(file_exists($view)) require_once($view); //Chargement de la vue
    	$content_for_layout = ob_get_clean(); //On stocke dans cette variable le contenu de la vue
    	
    	$alternativeLayoutFolder = substr_count($this->layout, DS) + substr_count($this->layout, '/');    	
    	if($alternativeLayoutFolder) { require_once $this->layout.'.php'; }
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
 */
    public function element($element, $vars = null, $inElementsFolder = true) {
        
    	if(isset($vars) && !empty($vars)) { 
    		
    		foreach($vars as $k => $v) { $this->vars[$k] = $v; } 
    	}    	
    	extract($this->vars);    
    	
    	$element = str_replace('/', DS, $element);
    	//if($element[0] != DS) { $element = ELEMENTS.DS.$element; }
    	//$element .= '.php'; //On rajoute l'extension
    	
    	//pr($element);
    	
    	if($inElementsFolder) { $element = ELEMENTS.DS.$element.'.php'; }
    	else { $element = $element.'.php'; }
    	
    	if(!file_exists($element)) { require ELEMENTS.DS.'backoffice'.DS.'missing_element.php'; } //Si le fichier n'existe pas on affiche un message d'erreur 
    	else { require $element; } //Sinon on le charge
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
 */
    function request($controller, $action, $parameters = array()) {
    	
    	$fileNameBase = strtolower(Inflector::underscore($controller));
    	
    	$file_name_default = strtolower($fileNameBase.'_controller'); //On récupère dans une variable le nom du controller    	
    	$file_path_default = CONTROLLERS.DS.$file_name_default.'.php'; //On récupère dans une variable le chemin du controller
    	
    	$file_name_plugin = strtolower($fileNameBase.'_plugin_controller'); //On récupère dans une variable le nom du controller
    	$file_path_plugin = PLUGINS.DS.$controller.DS.'controller.php'; //On récupère dans une variable le chemin du controller
    	    	
    	//////////////////////////////////////////////
    	//   RECUPERATION DES CONNECTEURS PLUGINS   //
    	$pluginsConnectors = get_plugins_connectors();
    	if(isset($pluginsConnectors[$fileNameBase])) {
    	
    		$connectorController = $pluginsConnectors[$fileNameBase];
    		$file_path_plugin = PLUGINS.DS.$connectorController.DS.'controller.php';
    	}
    	//////////////////////////////////////////////    	
    	if(file_exists($file_path_plugin)) { $file_path = $file_path_plugin; $file_name = $file_name_plugin; } //Sinon on teste si il y a un plugin
    	else if(file_exists($file_path_default)) { $file_path = $file_path_default; $file_name = $file_name_default; } //Si le controller par défaut existe
    	    	
    	//$fileName = strtolower(Inflector::underscore($controller).'_controller'); //On récupère dans une variable le nom du controller
    	//$filePath = CONTROLLERS.DS.$fileName.'.php'; //On récupère dans une variable le chemin du controller
    	require_once $file_path; //Inclusion de ce fichier si il existe
    	$controllerName = Inflector::camelize($file_name); //On transforme le nom du fichier pour récupérer le nom du controller
    	$c = new $controllerName(null, false); //Création d'une instance du controller souhaité
    	
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