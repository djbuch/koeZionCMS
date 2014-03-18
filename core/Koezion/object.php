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
 * @param varchar $name Nom du model à charger
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 18/03/2014 - Reprise du chargement des modèles des plugins
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
			$pluginPluralizeName = Inflector::pluralize($name);		
			$pluginUnderscoreName = Inflector::underscore($pluginPluralizeName);
			
			//////////////////////////////////////////////
			//   RECUPERATION DES CONNECTEURS PLUGINS   //
			$pluginsConnectors = get_plugins_connectors();
			if(isset($pluginsConnectors[$pluginUnderscoreName])) {
					
				$connectorModel = $pluginsConnectors[$pluginUnderscoreName];
				$file_path_plugin = PLUGINS.DS.$connectorModel.DS.'models'.DS.$file_name;	
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
			if($return) { return new $name($url); }
			else { $this->$name = new $name($url); } //Création d'un objet Model de type $name que l'on va instancier dans la classe
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
}