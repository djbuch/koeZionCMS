<?php
/**
 * Classe de configuration de l'application
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
class Configure {
    
	private $debug 			= 1; //Mode débug
	private $sessionName 	= 'KOEZION'; //Nom par défaut de la variable de session
	private $timerExec 		= 0;
	
/**
 * Cette fonction va renvoyer une instance de la classe car en static $this n'est pas accessible
 *
 * @return object Instance de la classe Validation
 * @version 0.1 - 28/12/2011
 * @version 0.2 - 10/11/2014 - Rajout dela visibilité de la fonction
 */	
	static function getInstance() {
		
		static $instance = array();
		//if(!$instance) { $instance[0] = &new Configure(); }
		if(!$instance) { $instance[0] = new Configure(); }
		return $instance[0];
	}	
	
/**
 * Accesseur en lecture des données de la classe
 *
 * @param varchar $var Variable à récupérer
 * @return mixed Valeur de la variable voulue
 * @version 0.1 - 29/12/2011
 */	
	static function read($var) {
				
		$_this = Configure::getInstance(); //On va créer une instence de la classe car en static l'objet $this n'est pas accessible
		$r_var = $_this->$var;
		return $r_var;		
	}
	
/**
 * Accesseur en écriture des données de la classe
 *
 * @param varchar $var Variable à modifier
 * @param mixed $value Valeur de la variable à modifier
 * @version 0.1 - 29/12/2011
 */	
	static function write($var, $value) {
				
		$_this = Configure::getInstance(); //On va créer une instence de la classe car en static l'objet $this n'est pas accessible
		$_this->$var = $value;
		unset($o);		
	}
	
/**
 * Cette fonction permet l'import de librairie dans le code
 *
 * @param 	varchar $librairie 	Chemin vers le fichier à importer
 * @param 	varchar $ext	 	Extension du fichier (php par défaut)
 * @access 	static
 * @author 	koéZionCMS
 * @version 0.1 - 16/05/2012 by FI
 */	
	static function import($librairie, $ext = 'php') {
		
		if(isset($ext)) $librairie = $librairie.'.'.$ext;
		require_once($librairie);		
	}
}