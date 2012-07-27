<?php
/**
 * Contrôleur permettant la gestion des erreurs
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
class ErrorsController extends AppController {   

/**
 * Ce contrôleur ne faisant référence à aucun modèle on n'en charge donc aucun
 *
 * @var 	boolean
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/03/2012 by FI
 */	
	public $auto_load_model = false;
	
//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	

/**
 * 
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 02/03/2012 by FI 
 */	
	function e404() {		

		header("HTTP/1.0 404 Not Found"); //Header 404
		$this->set('message', $this->request->kz_errors); //On envoi le message
	}
	
	function missing_controller($message) { 
		
		header("HTTP/1.0 404 Not Found"); //Header 404
		$this->set('message', $message); 
	}
	
	function missing_action($message) { 
		
		header("HTTP/1.0 404 Not Found"); //Header 404
		$this->set('message', $message); 
	}
}