<?php
/**
 * Contrôleur de gestion du plugin
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
class [PLUGIN_CLASS_NAME]Plugin extends KoeZionPlugin {
	
/**
 * Cette fonction permet la création des éléments nécessaires à l'installation et à la désinstallation du plugin
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - [DATE] by [AUTHOR]
 */	
	function __construct() {
		
		$this->path = dirname(__FILE__);		
		$sourcePath = dirname(__FILE__).DS.'install'; //Chemin vers le dossier d'installation du plugin
		
		//Définition des fichiers à copier
		/*$this->filesCopy = array(			
			array(
				'sourcePath' => $sourcePath,
				'destinationPath' => DESTINATION_PATH,
				'sourceName' => SOURCE_NAME,
				//'destinationName' => DESTINATION_NAME //Optionnal
			),
			array(
				'sourcePath' => $sourcePath.DS.FOLDER_TO_COPY,
				'destinationPath' => DESTINATION_PATH,
			)
		);*/

		//Chemin vers le fichier SQL contenant les requêtes à exécuter
		//$this->fileSql = file_get_contents($sourcePath.DS.'db.sql');		
	}
	
/**
 * Cette fonction permet l'initialisation des données frontoffice
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - [DATE] by [AUTHOR]
 */
	function beforeRender_frontoffice($controller, $datas = null) {
		
		$controllerName = $controller->params['controllerName']; //Contrôleur courant
		$actionName 	= $controller->params['action']; //Action courante
		$vars 			= $controller->get('vars'); //Liste des variables passées
	}	
	
/**
 * Cette fonction permet l'initialisation des données backoffice
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - [DATE] by [AUTHOR]
 */
	function beforeRender_backoffice($controller, $datas = null) {
		
		$controllerName = $controller->params['controllerName']; //Contrôleur courant
		$actionName 	= $controller->params['action']; //Action courante
		$vars 			= $controller->get('vars'); //Liste des variables passées
	}
}