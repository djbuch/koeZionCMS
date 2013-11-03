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
 * @version 0.1 - 26/02/2013 by FI - Modification de la gestion de l'installation des plugins
 */
	function backoffice_statut($id) {
	
		$elementStatus = parent::backoffice_statut($id, false); //On fait appel à la fonction d'édition parente		

		$conditions = array('conditions' => array('id' => $id));
		$plugin = $this->Plugin->findFirst($conditions);
		
		//On arrive dans ce cas si online = 0 et installed = 0
		//Du coup après la mise à jour du status on à online = 1 et installed = 0
		//Le plugin n'est donc pas en ligne et pas installé
		//On l'installe et on l'active 
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

		//On arrive dans ce cas si online = 1 et installed = 1
		//Du coup après la mise à jour du status on à online = 0 et installed = 1
		//Le plugin est donc en ligne et installé cela veut donc dire qu'on souhaite le passer hors ligne
		} else if(!$plugin['online'] && $plugin['installed']) {

			//Si le plugin est désactivé on désactive également le module
			$sqlModule = "UPDATE modules SET online = 0 WHERE plugin_id = ".$id.";";
			$this->Plugin->query($sqlModule);
			Session::setFlash('Le plugin est correctement désactivé'); 
		
		//On arrive dans ce cas si online = 0 et installed = 1
		//Du coup après la mise à jour du status online = 1 et installed = 1
		//Le plugin était donc installé mais hors ligne donc on le réactive
		} else if($plugin['online'] && $plugin['installed']) {

			//Si le plugin est désactivé on désactive également le module
			$sqlModule = "UPDATE modules SET online = 1 WHERE plugin_id = ".$id.";";
			$this->Plugin->query($sqlModule);
			Session::setFlash('Le plugin est correctement activé'); 
		}
		
		//On va contrôller la valeur du champ online pour le plugin et remettre à jour la table des modules		
		//if($plugin['online']) { $sqlModule = "UPDATE modules SET online = 1 WHERE plugin_id = ".$id.";"; }
		//else { $sqlModule = "UPDATE modules SET online = 0 WHERE plugin_id = ".$id.";"; }
		//$this->Plugin->query($sqlModule);
				
		FileAndDir::delete_directory_file(TMP.DS.'cache'.DS.'models'.DS); //Suppression du fichier de cache de la bdd
		FileAndDir::remove(TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS.'plugins.cache'); //Suppression du fichier de cache des plugins
				
		$this->redirect('backoffice/plugins/index'); //On retourne sur la page de listing
	}

/**
 * Cette fonction permet de désinstaller un plugin
 * 
 * 	Pour ce faire, elle va chercher directement dans la classe du fichier plugin.php les variables qui contiennent les informations nécessaires :
 * 		$plugin->filesCopy			La variable qui contient à la base la liste des fichiers à copier
 *		$plugin->sourceFolder		Détermine le dossier source des fichiers à copier. Le plus souvent, c'est " dirname(__FILE__).DS.'install' ", mais cela peut changer
 *
 * @param 	integer $id Identifiant du plugin à désinstaller
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 30/03/2013 by AA
 * @version 0.2 - 29/10/2013 by FI - Reprise de la fonction pour la terminer...
 */
	function backoffice_uninstall($id) {

		$errors = array();
		$plugin = $this->Plugin->findFirst(array('conditions' => array('id' => $id)));//On récupère le nom du plugin
		
		//On ne réalise la désinstallation que si le plugin est installé
		if($plugin['installed']) {

			$pluginName = Inflector::camelize($plugin['code']).'Plugin'; //Génération du nom du plugin			
			$pluginFile = PLUGINS.DS.$plugin['code'].DS.'plugin.php'; //Chemin vers le fichier d'installation du plugin
			
			if(FileAndDir::fexists($pluginFile)) { //Si le fichier plugin.php existe

				require_once($pluginFile); //Chargement du fichier
				$pluginClass = new $pluginName(); //Création d'un objet plugin

				//////////////////////////////////
				//			ACTIONS BDD			//
				//////////////////////////////////
												
				//On va récupérer l'identifiant du type de module à supprimer
				$modulesTypeIdResult = current($this->Plugin->query("SELECT DISTINCT(`modules_type_id`) FROM `modules` WHERE `plugin_id` = ".$id.";", true));
				$modulesTypeId = $modulesTypeIdResult['modules_type_id'];				
				
				$this->Plugin->query('DELETE FROM `modules` WHERE `plugin_id` = '.$id.';'); //On supprime tous les modules associés à ce plugin
				$this->Plugin->query('DELETE FROM `plugins` WHERE `id` = '.$id.';'); //On supprime le plugin dans la base de données
				$this->Plugin->query('DELETE FROM `modules_types` WHERE `id` = '.$modulesTypeId.';'); //On supprime le type de module associé à ce plugin
								
				//ON VA RECUPERER, SI IL Y EN A, LA LISTE DES TABLES ASSOCIEES AU PLUGIN EN COURS DE SUPPRESSION
				$databaseTables = $this->Plugin->table_list_in_database(); //Liste des tables de la BDD
				$databasePluginTables = array(); //Liste des tables du plugin
				$databasePluginTablesPrefix = 'plugins_'.$plugin['code'].'_'; //Préfix des tables du plugin
				
				//On va parcourir la liste des tables de la base de données pour en extraire les tables associées au plugin
				//Elles seront ensuite renommées en vue d'une suppression manuelle par le gestionnaire de la BDD
				foreach($databaseTables as $databaseTable) {
				
					if(substr_count($databaseTable, $databasePluginTablesPrefix)) { $sql .= "RENAME TABLE `".$databaseTable."` TO `_".$databaseTable."_".date("Ymd_His")."`;"; }
				}

				//////////////////////////////////
				//		ACTIONS FICHIERS		//
				//////////////////////////////////
				
				//Si des fichiers doivent être supprimés, on les récupère dans le plugin
				if(isset($pluginClass->filesCopy)) {

					foreach($pluginClass->filesCopy as $fileDelete) {

						if(isset($fileDelete['sourceName']) && isset($fileDelete['destinationName'])) {

							//Dans le cas ou on un seul fichier à supprimer
							$fileToDelete = $fileDelete['destinationPath'].DS.$fileDelete['destinationName']; //Chemin du fichier de destination
							$processResult = FileAndDir::remove($fileToDelete); //Suppression du fichier
							
						} else if(isset($fileDelete['sourcePath']) && isset($fileDelete['destinationPath'])) {
							
							//Dans le cas ou on a le contenu d'un dossier, il faut supprimer tous les fichiers qui ont été créés
							$sourcePathContent = FileAndDir::directoryContent($fileDelete['sourcePath']);
							foreach($sourcePathContent as $v) {

								$processResult = FileAndDir::remove($fileDelete['destinationPath'].DS.$v); //Suppression du fichier
							}
						}
						
						//Si l'index removePath est défini à true, alors on supprime le dossier en plus du fichier
						if(isset($fileDelete['removePath']) && $fileDelete['removePath'] == true) { FileAndDir::remove_directory($fileDelete['destinationPath']); }
						
						if(!$processResult) { $errors[] = $fileDelete['destinationName']; }
					}
					if(!empty($errors)) {
						
						Session::setFlash('Une erreur est survenue lors de la suppression de certains fichiers :<br />'.implode('<br />* ', $errors), 'error');
						$this->redirect('backoffice/plugins/index'); //On retourne sur la page de listing
						
					}
				}				
				
			} else {
				
				Session::setFlash("Le fichier plugin.php n'existe pas", 'error');
				$this->redirect('backoffice/plugins/index'); //On retourne sur la page de listing
								
			}
		} else {
			
			Session::setFlash('Le plugin doit être installé pour pouvoir être désinstallé', 'error');
			$this->redirect('backoffice/plugins/index'); //On retourne sur la page de listing
			
		}
		
		FileAndDir::delete_directory_file(TMP.DS.'cache'.DS.'models'.DS); //Suppression du fichier de cache de la bdd
		FileAndDir::remove(TMP.DS.'cache'.DS.'variables'.DS.'Plugins'.DS.'plugins.cache'); //Suppression du fichier de cache des plugins
		Session::setFlash('Le plugin a été correctement désinstallé');
		$this->redirect('backoffice/plugins/index'); //On retourne sur la page de listing
	}
	
/**
 * Cette fonction permet de contrôler les plugins qui ne sont pas encore insérés dans la base de données
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	protected function _check_plugins() {
		
		$pluginsDirectoryContent = FileAndDir::directoryContent(PLUGINS);
		foreach($pluginsDirectoryContent as $pluginDirectory) {

			if(is_dir(PLUGINS.DS.$pluginDirectory) && $pluginDirectory != "_") {
		
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
						}					
					}
				}
			}
		}		
	}
}