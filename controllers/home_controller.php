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
		$this->loadModel('Slider');
		$datas['sliders'] = $this->Slider->find(array(
			'conditions' => array('online' => 1), 
			'fields' => array('id', 'name', 'image', 'content'),
			'order' => 'order_by ASC, name ASC'
		));
		
		////////////////////////////////
		//   RECUPERATION DES FOCUS   //
		$this->loadModel('Focus');
		$datas['focus'] = $this->Focus->find(array(
			'conditions' => array('online' => 1), 
			'fields' => array('id', 'name', 'content'),
			'order' => 'order_by ASC, name ASC'
		)); 
		
		///////////////////////////////////
		//   RECUPERATION DES ARTICLES   //
		$this->loadModel('Post');		
		$postsQuery = array(
			'conditions' => array('online' => 1, 'display_home_page' => 1),
			'fields' => array('id', 'name', 'short_content', 'slug', 'display_link', 'modified_by', 'modified', 'prefix', 'category_id'),
			'limit' => '0, 5',
			'order' => 'modified DESC'
		);		
		$datas['posts'] = $this->Post->find($postsQuery);		
		
		///////////////////////////////////////////
		//   RECUPERATION DES TYPES D'ARTICLES   //
		$this->loadModel('PostsType');
		$datas['postsTypes'] = $this->PostsType->get_for_front(); 
		
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
		
	function backoffice_index() {}
	
	function backoffice_not_auth() {}
}