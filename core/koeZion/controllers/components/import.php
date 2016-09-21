<?php
class ImportComponent extends Component {

//////////////////////////////////////////////////////////////////////////////////////////
//								FONCTIONS PUBLIQUES										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Retourne le pointeur vers le fichier à importer
 *
 * @param 	varchar  	$file Chemin vers le fichier
 * @return 	ressource	Pointeur vers le fichier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/09/2016 by FI
 */
	public function open_file($file) {
		
		if(!empty($file)) {
			
			if(!substr_count($file, 'webroot/upload/')) { $filePath = realpath($_SERVER['DOCUMENT_ROOT'].str_replace('upload/', 'webroot/upload/', $file)); } 
			else { $filePath = realpath($_SERVER['DOCUMENT_ROOT'].$file); }
			
			return fopen($filePath, "r");
		}
	}

/**
 * Formate les données du fichier
 *
 * @param 	varchar  	$datas 			Données du fichier csv
 * @param 	varchar  	$type 			Type de données du fichier csv
 * @return 	array		Tableau des données à sauvegarder
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/09/2016 by FI
 */
	public function format_datas($datas, $type = 'user') {
		
		$datasToSave = array();
		if($type == 'user') {
		
			$datasToSave['lastname'] 		= isset($datas[0]) ? $this->_format_string($datas[0]) : '';	
			$datasToSave['firstname'] 		= isset($datas[1]) ? $this->_format_string($datas[1]) : '';	
			$datasToSave['mobile'] 			= isset($datas[2]) ? $this->_format_string($datas[2]) : '';	
			$datasToSave['email'] 			= isset($datas[3]) ? $this->_format_string($datas[3]) : '';	
			$datasToSave['password'] 		= isset($datas[4]) ? $this->_format_string($datas[4]) : '';					
		}
		
		return $datasToSave;
	}

/**
 * Formate une chaîne de caractère
 *
 * @param 	varchar  	$string Chaîne à traiter
 * @return 	varchar		Chaîne traitée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 21/09/2016 by FI
 */	
	protected function _format_string($string) {
		
		$string = str_replace("’", "'", $string);
		$string = str_replace("´", "'", $string);
		$string = str_replace("«", '"', $string);
		$string = str_replace("»", '"', $string);
		$string = str_replace('&', 'et', $string);
		return $string;
	}
}