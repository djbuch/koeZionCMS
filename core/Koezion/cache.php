<?php
/**
 * Permet la gestion des fichiers de caches
 *
 */
class Cache {
	
	static $extention = '.cache';
	
/**
 * 
 * Cette fonction permet de tester l'existence d'un fichier de cache
 * Elle retourne faux si il n'existe pas, vrai si il existe et par défaut elle renvoit le contenu du fichier
 * 
 * @param 	varchar $cacheFolder 	Dossier de stockage du fichier
 * @param 	varchar $cacheFile 		Nom du fichier
 * @param 	boolean $returnDatas 	Indique si on retourne les données ou vrai
 * @return 	mixed soit un booléen soit le contenu du ficher
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 19/12/2012 by FI
 */	
	function exists_cache_file($cacheFolder, $cacheFile, $returnDatas = true) {

		if(!file_exists($cacheFolder.$cacheFile.Cache::$extention)) { return false; }
		else if($returnDatas) { return Cache::get_cache_file_content($cacheFolder, $cacheFile); }
		else { return true; }
	}	
	
/**
 * 
 * Cette fonction permet de créer un fichier de cache
 * Par défaut elle teste si le dossier de destination existe et le créé le cas échéant
 * 
 * @param 	varchar $cacheFolder 	Dossier de stockage du fichier
 * @param 	varchar $cacheFile 		Nom du fichier
 * @param 	array 	$datas 			Données à sauvegarder
 * @return 	boolean si le processus s'est correctement déroulé retourne vrai
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 19/12/2012 by FI
 */	
	function create_cache_file($cacheFolder, $cacheFile, $datas) {
		
		FileAndDir::createPath($cacheFolder);
		return FileAndDir::put($cacheFolder.$cacheFile.Cache::$extention, serialize($datas));
		/*$pointeur = fopen($file, 'w');
		fwrite($pointeur, serialize($datas));
		fclose($pointeur);*/		
	}	
	
/**
 * 
 * Cette fonction permet de récupérer le contenu d'un fichier de cache
 * 
 * @param 	varchar $cacheFolder 	Dossier de stockage du fichier
 * @param 	varchar $cacheFile 		Nom du fichier
 * @return 	array données contenues dans le fichier
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 19/12/2012 by FI
 */	
	function get_cache_file_content($cacheFolder, $cacheFile) {
		
		return unserialize(FileAndDir::get($cacheFolder.$cacheFile.Cache::$extention));
	}	
	
/**
 * 
 * Cette fonction permet de supprimer un fichier de cache
 * 
 * @param 	varchar $cacheFolder 	Dossier de stockage du fichier
 * @param 	varchar $cacheFile 		Nom du fichier
 * @return 	boolean vrai si la suppression s'est correctement déroulée
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 19/12/2012 by FI
 */	
	function delete_cache_file_content($cacheFolder, $cacheFile) {
	
		return FileAndDir::remove($cacheFolder.$cacheFile.Cache::$extention);
	}
	

}