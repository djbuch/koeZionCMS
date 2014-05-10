<?php
/**
 * Classe parente des plugins
 * 
 * Permet d'effctuer les opérations suivantes : 
 * 		- Copie des fichiers de configuration
 * 		- Exécution de la requête d'installation
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
class KoeZionPlugin extends Object {
	
/**
 * Chemin d'accès au plugin
 * 
 * @var 	boolean/varchar
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2014 by FI
 */
	public $path = false;
	
/**
 * Fichier SQL contenant les requêtes à exécuter lors de l'installation/création d'un plugin
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2014 by FI
 */
	public $fileSql = false;
	
/**
 * Fichier SQL contenant les requêtes à exécuter lors de la suppression d'un plugin
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2014 by FI
 */
	public $fileSqlDelete = false;
	
/**
 * Liste des fichiers à copier lors de l'installation/création d'un plugin
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2014 by FI
 */
	public $filesCopy = false;
	
/**
 * Cette fonction permet de lancer l'installation du plugin
 * 
 * @param 	object $controller Contrôleur Plugin
 * @return	boolean	Vrai si la l'installation s'est correctement déroulée, faux sinon 
 * @access	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2014 by FI
 */
	 public function install($controller) {
		
		/////////////////////////////////////////////////////
		//    ON CHECK SI UN FICHIER SQL EST A EXECUTER    //
		$sqlResult = true; //Par défaut par d'erreur SQL
		if($this->fileSql) { $sqlResult = $controller->Plugin->query($this->fileSql); } //Si un fichier est défini on l'exécute
				
		//////////////////////////////////////////////////
		//    ON CHECK SI DES FICHIERS SONT A COPIER    //
		$filesCopyResult = true; //Par défaut pas d'erreur de copie
		if($this->filesCopy) { $filesCopyResult = $this->_files_copy($this->filesCopy); } //Si des fichiers sont à copier on lance le process
		
		return $sqlResult && $filesCopyResult; //On retourne les résultats de l'exécution du fichier SQL et de la copie des fichiers
	}

/**
 * Cette fonction permet d'éffectuer la copie de l'ensemble des fichiers de configurations d'un plugin
 * 
 * @param 	array $filesCopy Liste des fichiers à copier
 * @return	boolean	Vrai si la copie s'est correctement déroulée, faux sinon 
 * @access	protected
 * @author	koéZionCMS
 * @version 0.1 - 09/05/2014 by FI
 */	
	protected function _files_copy($filesCopy) {
		
		$processResult = true;
		
		//Parcours des fichiers à copier
		foreach($filesCopy as $fileCopy) {
		
			FileAndDir::createPath($fileCopy['destinationPath']); //Création du dossier de destination
		
			//Dans le cas ou on un seul fichier à copier
			if(isset($fileCopy['sourceName'])) {
		
				$sourceFile = $fileCopy['sourcePath'].DS.$fileCopy['sourceName']; //Chemin du fichier source
				
				//On récupère le nom du fichier de destination (par défaut ce sera le même que la source)
				$destinationName = isset($fileCopy['destinationName']) ? $fileCopy['destinationName'] : $fileCopy['sourceName'];
				$destinationFile = $fileCopy['destinationPath'].DS.$destinationName; //Chemin du fichier de destination
				$processResult = FileAndDir::fcopy($sourceFile, $destinationFile); //Copie du fichier
		
			//Dans le cas ou on a le contenu d'un dossier à copier
			} else {
		
				$sourcePathContent = FileAndDir::directoryContent($fileCopy['sourcePath']); //On récupère l'ensemble des fichiers
				//On parcours le tout et on copie
				foreach($sourcePathContent as $v) { $processResult = FileAndDir::fcopy($fileCopy['sourcePath'].DS.$v, $fileCopy['destinationPath'].DS.$v); }
			}
		}
		
		return $processResult;
	}
}