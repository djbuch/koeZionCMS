<?php
class Import {

//////////////////////////////////////////////////////////////////////////////////////////
//								FONCTIONS PUBLIQUES										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Retourne le pointeur vers le fichier à importer
 *
 * @param 	varchar  	$file 			Chemin vers le fichier
 * @return 	ressource	Pointer vers le fichier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/07/2012 by FI
 */
	function open_file($file) {
		
		//On rajoute le dossier webroot car on va travailler directement depuis la racine du serveur
		$filePath = realpath($_SERVER['DOCUMENT_ROOT'].str_replace('upload/', 'webroot/upload/', $file));
		return fopen($filePath, "r");
	}

/**
 * Formate les données du fichier en fonction de la catégorie du produit
 *
 * @param 	varchar  	$datas 			Données du fichier csv
 * @param 	integer  	$categoryId 	Identifiant de la catégorie
 * @return 	array		Tableau des données à sauvegarder
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/07/2012 by FI
 */
	function format_catalogue_datas($datas, $categoryId) {
	
		//Les fichiers d'import on une base commune sur certains champs on va donc les traiter avant		
		$datasToSave['reference'] 		= utf8_encode($datas[0]);
		$datasToSave['name'] 			= utf8_encode($datas[1]);
		$datasToSave['price'] 			= utf8_encode($datas[2]);
		$datasToSave['disponibility'] 	= utf8_encode($datas[3]);
		$datasToSave['is_coup_coeur'] 	= utf8_encode($datas[4]);
		$datasToSave['online'] 			= 1;
		$datasToSave['category_id'] 	= $categoryId;		
		
		return $datasToSave;
	}	
}