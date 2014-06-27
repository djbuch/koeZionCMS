<?php
/**
 * Contrôleur permettant la gestion de la home page du frontoffice et du backoffice
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
class HomeController extends AppController {   

/**
 * Ce contrôleur ne faisant référence à aucun modèle on n'en charge donc aucun
 *
 * @var 	boolean
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	//public $auto_load_model = false;
	
//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction permet l'affichage de la page d'accueil du site
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI 
 * @version 0.2 - 12/03/2012 by FI - Modification de la récupération des types d'articles 
 */	
	function index() {
		
		//////////////////////////////////
		//   RECUPERATION DES SLIDERS   //		
		$sliders = $this->_get_sliders();
		$datas['sliders'] = $sliders;		
		
		
		////////////////////////////////
		//   RECUPERATION DES FOCUS   //	
		$focus = $this->_get_focus();
		$datas['focus'] = $focus; 
		
		///////////////////////////////////
		//   RECUPERATION DES ARTICLES   //
		$posts = $this->_get_posts();
		$datas['posts'] = $posts;				
		
		///////////////////////////////////////////
		//   RECUPERATION DES TYPES D'ARTICLES   //
		$postsTypes = $this->_get_posts_types();
		$datas['postsTypes'] = $postsTypes;				 
		
		$datas['breadcrumbs'] = array(); 				
		
		$this->set($datas); //On fait passer les données à la vue
	}
	
/**
 * Cette fonction permet l'affichage des erreurs 404
 *
 * @version 0.1 - 07/08/2012
 */
	function e404() { header("HTTP/1.0 404 Not Found"); }
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
	function backoffice_not_auth() {}
}