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
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Sliders'.DS;
		$cacheFile 		= "website_".CURRENT_WEBSITE_ID;
		
		$sliders = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$sliders) {
		
			$this->loadModel('Slider');
			$sliders = $this->Slider->find(array(
				'conditions' => array('online' => 1), 
				'fields' => array('id', 'name', 'image', 'content'),
				'order' => 'order_by ASC, name ASC'
			));
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $sliders);
		}
		$datas['sliders'] = $sliders;		
		
		
		////////////////////////////////
		//   RECUPERATION DES FOCUS   //
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Focus'.DS;
		$cacheFile 		= "website_".CURRENT_WEBSITE_ID;
		
		$focus = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$focus) {
			
			$this->loadModel('Focus');
			$focus = $this->Focus->find(array(
				'conditions' => array('online' => 1), 
				'fields' => array('id', 'name', 'content'),
				'order' => 'order_by ASC, name ASC'
			));
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $focus);
		}
		$datas['focus'] = $focus; 
		
		///////////////////////////////////
		//   RECUPERATION DES ARTICLES   //
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS;
		$cacheFile 		= "home_page_website_".CURRENT_WEBSITE_ID;
		
		$posts = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$posts) {	
			
			$postsQuery = array(
				'conditions' => array('online' => 1, 'display_home_page' => 1),
				'limit' => '0, 5'
			);	
		
			//////////////////////////////////////////////////////
			//   RECUPERATION DES CONFIGURATIONS DES ARTICLES   //
			require_once(LIBS.DS.'config_magik.php'); 										//Import de la librairie de gestion des fichiers de configuration des posts
			$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'posts.ini', false, false); 		//Création d'une instance
			$postsConfigs = $cfg->keys_values();											//Récupération des configurations
			//////////////////////////////////////////////////////
		
			if($postsConfigs['order'] == 'modified') { $postsQuery['order'] = 'modified DESC'; }
			else if($postsConfigs['order'] == 'created') { $postsQuery['order'] = 'created DESC'; }
			else if($postsConfigs['order'] == 'order_by') { $postsQuery['order'] = 'order_by ASC'; }			
			
			$this->loadModel('Post');		
			$posts = $this->Post->find($postsQuery);
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $posts);
		}
		$datas['posts'] = $posts;
				
		
		///////////////////////////////////////////
		//   RECUPERATION DES TYPES D'ARTICLES   //
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'PostsTypes'.DS;
		$cacheFile 		= "home_page_website_".CURRENT_WEBSITE_ID;
		
		$postsTypes = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$postsTypes) {			
			
			$this->loadModel('PostsType');
			$postsTypes = $this->PostsType->get_for_front();
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $postsTypes);
		}
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