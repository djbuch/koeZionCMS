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
		$datasToSave['disponibility'] 	= utf8_encode($datas[2]);
		$datasToSave['price'] 			= utf8_encode($datas[3]);
		$datasToSave['is_coup_coeur'] 	= utf8_encode($datas[4]);
		$datasToSave['online'] 			= 1;
		$datasToSave['category_id'] 	= $categoryId;
		//$datasToSave['slug'] 			= strtolower(Inflector::slug($datasToSave['name'], '-'));
		$datasToSave['prefix'] 			= PRODUCT_PREFIX;		
		
		switch($categoryId) {
			
			case 23: //RESERVAVINS
			case 100: //RESERVAVINS BOUCLE LOCALE
				
				$datasToSave['country'] 		= utf8_encode($datas[5]);
				$datasToSave['color'] 			= utf8_encode($datas[6]);
				$datasToSave['capacity'] 		= utf8_encode($datas[7]);
			break;
			
			case 24: //VINS EN BOUTEILLE
			case 101: //VINS EN BOUTEILLE
				
				$datasToSave['country'] 		= utf8_encode($datas[5]);
				$datasToSave['color'] 			= utf8_encode($datas[6]);
			break;
			
			case 25: //ALCOOLS
			case 106: //ALCOOLS
				
				$datasToSave['type'] 			= utf8_encode($datas[5]);
			break;
			
			case 26: //BIERES
			case 102: //BIERES
				
				$datasToSave['color'] 			= utf8_encode($datas[5]);
				$datasToSave['containing'] 		= utf8_encode($datas[6]);
				$datasToSave['origin'] 			= utf8_encode($datas[7]);
			break;
			
			case 27: //EPICERIE FINE
			case 103: //EPICERIE FINE
				
				$datasToSave['type'] 			= utf8_encode($datas[5]);
			break;
			
			case 28: //ACCESSOIRES
			case 104: //ACCESSOIRES
				
				$datasToSave['type'] 			= utf8_encode($datas[5]);
			break;
			
			//case 29: //EMBALLAGES
			//case 105: //EMBALLAGES
			//break;
		}
		
		return $datasToSave;
	}	
}