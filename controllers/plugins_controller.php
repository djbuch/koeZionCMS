<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des plugins
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
class PluginsController extends AppController {

/**
 * Cette fonction permet l'affichage de la liste des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 21/05/2012 by FI - Rajout d'une condition sur la récupération des catégories
 */
	function backoffice_index() {
	
		$this->_check_plugins();
		parent::backoffice_index();
	}
	
	function backoffice_edit() { $this->redirect('backoffice/plugins/index'); }	
	function backoffice_delete() { $this->redirect('backoffice/plugins/index'); }	
	
	
/**
 * Cette fonction permet la mise à jour du statut d'un élement directement depuis le listing
 *
 * @param 	integer $id Identifiant de l'élément dont le statut doit être modifié
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 24/09/2012 by FI
 */
	function backoffice_statut($id) {
	
		$elementStatus = parent::backoffice_statut($id, false); //On fait appel à la fonction d'édition parente		

		$conditions = array('conditions' => array('id' => $id));
		$plugin = $this->Plugin->findFirst($conditions);
		
		if($plugin['online'] && !$plugin['installed']) {
						
			$isInstalled = false; //Par défaut le plugin est considéré comme non installé
			
			$pluginName = Inflector::camelize($plugin['code']).'Plugin'; //Génération du nom du plugin			
			$pluginFile = PLUGINS.DS.$plugin['code'].DS.'plugin.php'; //Chemin vers le fichier d'installation du plugin
			
			if(FileAndDir::fexists($pluginFile)) { //Si le fichier existe
						
				require_once($pluginFile); //Chargement du fichier
				$pluginClass = new $pluginName(); //Création d'un objet plugin
				
				if(method_exists($pluginClass, '_install')) { //On teste si le plugin possède une méthode d'installation
					
					if($pluginClass->_install($this)) { $isInstalled = true; } //Si oui on la lance
				} 
				else { $isInstalled = true; } //Si non on considère qu'il est installé
				
			} else { $isInstalled = true; } //Si non on considère qu'il est installé
			
			//Si le plugin est installé on va le sauvegarder en bdd
			if($isInstalled) {
				
				$this->Plugin->save(array('id' => $id, 'installed' => 1));				
				Session::setFlash('Le plugin est correctement installé');		
						
			} else { Session::setFlash("Problème lors de l'installation du plugin", 'error'); }		
			
		} else if(!$plugin['online']) {

			//Si le plugin est désactivé on désactive également le module
			 
		
			Session::setFlash('Le plugin est correctement désactivé'); 
		}
		
		//On va contrôller la valeur du champ online pour le plugin et remettre à jour la table des modules		
		if($plugin['online']) { $sqlModule = "UPDATE modules SET online = 1 WHERE plugin_id = ".$id.";"; }
		else { $sqlModule = "UPDATE modules SET online = 0 WHERE plugin_id = ".$id.";"; }
		$this->Plugin->query($sqlModule);
		
		
		FileAndDir::delete_directory_file(TMP.DS.'cache'.DS.'models'.DS); //Suppression du fichier de cache de la bdd
		FileAndDir::remove(TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS.'plugins.cache'); //Suppression du fichier de cache des plugins
				
		$this->redirect('backoffice/plugins/index'); //On retourne sur la page de listing
	}	
	
	function _check_plugins() {
		
		$pluginsDirectoryContent = FileAndDir::directoryContent(PLUGINS);
		foreach($pluginsDirectoryContent as $pluginDirectory) {

			if($pluginDirectory != "_") {
		
				$pluginDirectoryContent = FileAndDir::directoryContent(PLUGINS.DS.$pluginDirectory);
				if(file_exists(PLUGINS.DS.$pluginDirectory.DS.'description.xml')) {
			
					$xParsedXml = simplexml_load_file(PLUGINS.DS.$pluginDirectory.DS.'description.xml');
					$xParsedXml = (array)$xParsedXml;
			
					$this->loadModel('Module'); //On charge le modèle permettant la gestion des modules
					foreach($xParsedXml as $k => $v) {
						
						$conditions = array('conditions' => array('code' => $xParsedXml['code']));
						$plugin = $this->Plugin->find($conditions);
						
						if(count($plugin) == 0) {
							
							//Insertion dans la base de données
							$insertPlugin = array(
								'code' => $xParsedXml['code'],
								'name' => $xParsedXml['name'],
								'description' => $xParsedXml['description'],
								'author' => $xParsedXml['author'],
								'online' => 0,
								'installed' => 0
							);
							$this->Plugin->save($insertPlugin);
							
							
							if($xParsedXml['display_in_menu']) {
							
								//On va également le rajouter dans la gestion des menus
								$moduleDatas = array(
									'name' => $xParsedXml['name'],
									'controller_name' => $xParsedXml['code'],
									'online' => 0, //Le plugin n'est pas activé donc idem pour le module
									'modules_type_id' => 6, //Type de module plugin
									'plugin_id' => $this->Plugin->id
								);
								$this->Module->save($moduleDatas);
							}
						}					
					}
				}
			}
		}		
	}
}