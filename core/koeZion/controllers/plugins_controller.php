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
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */
	public function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {
	
		$this->_check_plugins();
		parent::backoffice_index();
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 * Elle permet la création des différents dossiers et fichier de base
 * Cette fonction ne procède pas à l'insertion en base de données 
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/05/2016 by FI
 */	
	public function backoffice_add($redirect = true, $forceInsert = false) { 
	
		//Si des données sont postées
		if($this->request->data) {
			
			//Si elles sont valides
			if($this->Plugin->validates($this->request->data)) {
				
				$pluginCode 		= strtolower(Inflector::slug($this->request->data['code']));
				$pluginClassName	= Inflector::camelize($pluginCode);
				$pluginName 		= $this->request->data['name'];
				$pluginDescription 	= $this->request->data['description'];
				$pluginAuthor 		= $this->request->data['author'];
				$pluginBasePath 	= PLUGINS.DS.$pluginCode;
				
				
				/////////////////////////////////
				//    CREATION DES DOSSIERS    //
					$fordersToCreate = array(
						$pluginBasePath,
						$pluginBasePath.DS.'controllers',
						$pluginBasePath.DS.'controllers'.DS.'components',
						$pluginBasePath.DS.'install',
						$pluginBasePath.DS.'models',
						$pluginBasePath.DS.'models'.DS.'behaviors',
						$pluginBasePath.DS.'versions',
						$pluginBasePath.DS.'views',
						$pluginBasePath.DS.'views'.DS.'elements',
						$pluginBasePath.DS.'views'.DS.'helpers'				
					);
					foreach($fordersToCreate as $forderToCreate) { FileAndDir::createPath($forderToCreate); }
				
				/////////////////////////////////
				//    CREATION DES FICHIERS    //
					FileAndDir::put($pluginBasePath.DS.'description.xml', '');
				
					//////////////////////////////
					//    FICHIER PLUGIN.PHP    //
						//Lecture du fichier de base
						$pluginPhpContent = FileAndDir::get(KOEZION.DS.'files'.DS.'plugins'.DS.'plugin.php');
						
						//Remplacement des données
						$pluginPhpContent = str_replace('[PLUGIN_CLASS_NAME]', $pluginClassName, $pluginPhpContent);
						$pluginPhpContent = str_replace('[DATE]', date('d/m/Y'), $pluginPhpContent);
						$pluginPhpContent = str_replace('[AUTHOR]', $pluginAuthor, $pluginPhpContent);
						
						//Ecriture dans le fichier de destination
						FileAndDir::put($pluginBasePath.DS.'plugin.php', $pluginPhpContent);
				
					///////////////////////////////////
					//    FICHIER DESCRIPTION.XML    //
						//Lecture du fichier de base
						$descriptionXmlContent = FileAndDir::get(KOEZION.DS.'files'.DS.'plugins'.DS.'description.xml');
						
						//Remplacement des données
						$descriptionXmlContent = str_replace('[CODE]', $pluginCode, $descriptionXmlContent);
						$descriptionXmlContent = str_replace('[NAME]', $pluginName, $descriptionXmlContent);
						$descriptionXmlContent = str_replace('[DESCRIPTION]', $pluginDescription, $descriptionXmlContent);
						$descriptionXmlContent = str_replace('[AUTHOR]', $pluginAuthor, $descriptionXmlContent);
						
						//Ecriture dans le fichier de destination
						FileAndDir::put($pluginBasePath.DS.'description.xml', $descriptionXmlContent);
					
					////////////////////////////////
					//    FICHIER VERSIONS.XML    //
						//Lecture du fichier de base
						$versionsXmlContent = FileAndDir::get(KOEZION.DS.'files'.DS.'plugins'.DS.'versions.xml');
						
						//Remplacement des données
						$versionsXmlContent = str_replace('[DATE]', date('d/m/Y'), $versionsXmlContent);
						$versionsXmlContent = str_replace('[NUM]', date('Ymd'), $versionsXmlContent);
						$versionsXmlContent = str_replace('[SUPERVISOR]', $pluginAuthor, $versionsXmlContent);
						
						//Ecriture dans le fichier de destination
						FileAndDir::put($pluginBasePath.DS.'versions'.DS.'versions.xml', $versionsXmlContent);
					
					////////////////////////////////////
					//    FICHIER VERSIONS_BDD.XML    //
						//Lecture du fichier de base
						$versionsBddXmlContent = FileAndDir::get(KOEZION.DS.'files'.DS.'plugins'.DS.'versions_bdd.xml');
						
						//Remplacement des données
						$versionsBddXmlContent = str_replace('[DATE]', date('d/m/Y'), $versionsBddXmlContent);
						$versionsBddXmlContent = str_replace('[NUM]', date('Ymd'), $versionsBddXmlContent);
						$versionsBddXmlContent = str_replace('[SUPERVISOR]', $pluginAuthor, $versionsBddXmlContent);
						
						//Ecriture dans le fichier de destination
						FileAndDir::put($pluginBasePath.DS.'versions'.DS.'versions_bdd.xml', $versionsBddXmlContent);
						
				$this->redirect('backoffice/plugins/index');						
			}
		}
		
		
	}	
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	public function backoffice_edit($id = null, $redirect = true) { $this->redirect('backoffice/plugins/index'); }	
	
/**
 * Cette fonction permet la suppression d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	public function backoffice_delete($id, $redirect = true) { $this->redirect('backoffice/plugins/index'); }
	
/**
 * Cette fonction permet la mise à jour du statut d'un élement directement depuis le listing
 *
 * @param 	integer $id Identifiant de l'élément dont le statut doit être modifié
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 24/09/2012 by FI
 * @version 0.2 - 26/02/2013 by FI - Modification de la gestion de l'installation des plugins
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */
	public function backoffice_statut($id, $redirect = true) {
	
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
			
			$isInstalled = true; //Par défaut on considère que le plugin est installé
			if(FileAndDir::fexists($pluginFile)) { //Si le fichier existe
						
				require_once($pluginFile); //Chargement du fichier
				$pluginClass = new $pluginName(); //Création d'un objet plugin				
				$isInstalled = $pluginClass->install($this); //on lance le process d'installation
				
				/*if(method_exists($pluginClass, 'install')) { //On teste si le plugin possède une méthode d'installation
					
					if($pluginClass->install($this)) { $isInstalled = true; } //Si oui on la lance
				} 
				else { $isInstalled = true; } //Si non on considère qu'il est installé*/
				
			} /*else { $isInstalled = true; }*/ //Si non on considère qu'il est installé
			
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
 * @version 0.2 - 24/09/2015 by FI - Reprise de la gestion de la suppression des tables et des fichiers d'un plugin. Maintenant on supprime toutes les tables et on en fait un backup. Les fichiers du plugin ne sont plus sauvegardés.
 */
	public function backoffice_uninstall($id) {

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
																			
					$sql[] = 'DELETE FROM `modules` WHERE `plugin_id` = '.$id.';'; //Requête de suppression des modules
					$sql[] = 'DELETE FROM `modules_types` WHERE `plugin_id` = '.$id.';'; //Requête de suppression du type de module
					$sql[] = 'DELETE FROM `plugins` WHERE `id` = '.$id.';'; //Requête de suppression du plugin
														
					//ON VA RECUPERER, SI IL Y EN A, LA LISTE DES TABLES ASSOCIEES AU PLUGIN EN COURS DE SUPPRESSION
					$databaseTables 			= $this->Plugin->table_list_in_database(); //Liste des tables de la BDD
					$databasePluginTables 		= array(); //Liste des tables du plugin (utilisée pour la sauvegarde)
					$databasePluginTablesPrefix = 'plugins_'.$plugin['code']; //Préfix des tables du plugin
										
					//On va parcourir la liste des tables de la base de données pour en extraire les tables associées au plugin
					//Elles seront ensuite supprimées mais une sauvegarde sera effectuée avant									
					foreach($databaseTables as $databaseTable) {
											
						if(substr_count($databaseTable, $databasePluginTablesPrefix)) { 
							
							//Suppression du rename car la longueur des noms des tables posait problème
							//$sql[] 				= "RENAME TABLE `".$databaseTable."` TO `_".$databaseTable."_".date("Ymd_His")."`;";
							$sql[] 					= "DROP TABLE `".$databaseTable."`;";
							$databasePluginTables[] = array('table' => $databaseTable);
						}
					}					
	
					//Si on a des requêtes supplémentaires à exécuter
					//Prévu pour supprimer les types de modules par exemple
					if($pluginClass->fileSqlDelete) { $sql[] = $pluginClass->fileSqlDelete; }
										
					//Création du dossier de backup
					if($pluginClass->path) {
					
						$deletePath = $pluginClass->path.DS.'delete'.DS.date("Ymd_His");
						FileAndDir::createPath($deletePath);
					}
					
					//Sauvegarde des tables du plugin
					if($databasePluginTables) {
						
						//Backup de la base de données
						$databaseBackup = $this->components['Database']->export_database($this->Plugin, $databasePluginTables, true);
												
						$handle = fopen($deletePath.DS.'database.sql', 'w');
						fwrite($handle, $databaseBackup);
						fclose($handle);
					}
										
					$sql = implode("\n", $sql);
					$this->Plugin->query($sql);
								
				//////////////////////////////////
				//		ACTIONS FICHIERS		//
				//////////////////////////////////
				//Si des fichiers doivent être supprimés, on les récupère dans le plugin
				//A REPRENDRE VOIR EVENTUELLEMENT SI ON SUPPRIME PAS LA CLASSE FileAndDir???
					if(isset($pluginClass->filesCopy)) {
										
						foreach($pluginClass->filesCopy as $fileDelete) {
	
							if(isset($fileDelete['sourceName'])) {
				
								//On récupère le nom du fichier de destination (par défaut ce sera le même que la source)
								$destinationName = isset($fileDelete['destinationName']) ? $fileDelete['destinationName'] : $fileDelete['sourceName'];
								$fileToDelete = $fileDelete['destinationPath'].DS.$destinationName; //Chemin du fichier de destination
								
								//if(isset($deletePath)) { FileAndDir::fcopy($fileToDelete, $deletePath.DS.$destinationName); } //Sauvegarde du fichier								
								$processResult = FileAndDir::remove($fileToDelete); //Suppression du fichier
								
								if(!$processResult) { $errors[] = $fileToDelete; }
								
							} else {
								
								//Backup des fichiers, on va copier le dossier parent
								$pathInfos = pathinfo($fileDelete['destinationPath']);
								//FileAndDir::createPath($deletePath.DS.$pathInfos['filename']);
								//FileAndDir::recursive_copy($fileDelete['destinationPath'], $deletePath.DS.$pathInfos['filename']);
								FileAndDir::recursive_delete($fileDelete['destinationPath']);
							}
							
							//Si l'index removePath est défini à true, alors on supprime le dossier en plus du fichier
							if(isset($fileDelete['removePath']) && $fileDelete['removePath'] == true) { FileAndDir::remove_directory($fileDelete['destinationPath']); }
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
 * Cette fonction permet d'installer un plugin par un fichier ZIP
 * 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 05/02/2015 by FI
 */
	public function backoffice_install_by_zip() {
		
		if($this->request->data) {

			//Récupération de l'archive
			$pluginZipFile = $this->request->data['plugin_zip_file'];
			
			//Test de l'archive
			$validation = new Validation();
			if(!$validation->checkUpload($pluginZipFile)) {
			
				Session::setFlash(_('Archive ZIP vide'), 'error');
				$this->redirect('backoffice/plugins/index');
			}
			if(!$validation->checkType($pluginZipFile, array('application/octet-stream'))) {
			
				Session::setFlash(_('Archive ZIP obligatoire'), 'error');
				$this->redirect('backoffice/plugins/index');
			}
			
			//Upload de l'archive
			$this->Plugin->files_to_upload = array('plugin_zip_file' => array('path' => TMP.DS.'upload'));
			$this->Plugin->upload_files(array('plugin_zip_file' => $pluginZipFile), null);
			
			$pluginZipFilePath = $this->Plugin->files_to_upload['plugin_zip_file']['path'].DS.$this->Plugin->files_to_upload['plugin_zip_file']['uploaded_name'];
			
			///////////////////////////////////////////////
			//    PROCESSUS D'EXTRACTION DE L'ARCHIVE    //
				$zipArchive = new ZipArchive() ;
				
				//Ouverture de l'archive
				if($zipArchive->open($pluginZipFilePath) !== true) { 
				
					Session::setFlash(_("Impossible d'ouvrir l'archive ZIP"), 'error');
					$this->redirect('backoffice/plugins/index'); 
				}
				
				//Extraction du contenu de l'archive dans le dossier de destination
				$zipArchive->extractTo(PLUGINS);
				
				//Fermeture de l'archive
				$zipArchive->close();
				
			Session::setFlash(_('Archive ZIP correctement extraite'));
			$this->redirect('backoffice/plugins/index');
			
		} else {
			
			Session::setFlash('Aucun fichier à installer', 'error');
			$this->redirect('backoffice/plugins/index');			
		}
	}
	
/**
 * Cette fonction permet de contrôler les plugins qui ne sont pas encore insérés dans la base de données
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 06/05/2015 by FI - Rajout du contrôle $pluginDirectory[0] != '_'
 */	
	protected function _check_plugins() {
		
		$pluginsDirectoryContent = FileAndDir::directoryContent(PLUGINS);
		foreach($pluginsDirectoryContent as $pluginDirectory) {

			if(is_dir(PLUGINS.DS.$pluginDirectory) && $pluginDirectory != "_" && $pluginDirectory[0] != '_') {
		
				if(file_exists(PLUGINS.DS.$pluginDirectory.DS.'description.xml')) {
			
					$xParsedXml = simplexml_load_file(PLUGINS.DS.$pluginDirectory.DS.'description.xml');
					$xParsedXml = (array)$xParsedXml;
			
					$this->load_model('Module'); //On charge le modèle permettant la gestion des modules
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