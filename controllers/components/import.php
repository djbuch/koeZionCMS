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
}