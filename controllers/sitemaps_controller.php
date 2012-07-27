<?php
/**
 * Permet de gérer la carte du site
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
class SitemapsController extends Controller {   

	public $auto_load_model = false; //On indique que ce contrôleur n'a pas de modèle
	
/**
 * Fonction qui va permettre la récupération des différentes urls du site
 * Cette fonction va récupérer les éléments suivants : 
 * 		- Catégories
 * 		- Articles
 * 		- Types d'articles
 * 		- Rédacteurs
 * 		- Dates de publication
 * 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 */	
	function index() {		
		
		$this->layout = 'sitemaps'; //Définition du layout à utiliser
		
		/////////////////////////////////////////////////
		//   RECUPERATION DES DIFFERENTES CATEGORIES   //
		$this->loadModel('Category');
		$sitemaps['categories'] = $this->Category->find(
			array(
				'fields' => array('id', 'name', 'slug'),
				'order' => 'lft',
				'conditions' => array('online' => 1)					
			)
		);		
		
		///////////////////////////////////////////
		//   RECUPERATION DE TOUS LES ARTICLES   //
		$this->loadModel('Post');
		$sitemaps['posts'] = $this->Post->find(
			array(
				'fields' => array('id', 'name', 'slug', 'prefix', 'modified'),
				'order' => 'modified DESC',
				'conditions' => array('online' => 1)
			)
		);
		$this->unloadModel('Post');
		
		///////////////////////////////////////////////////
		//   RECUPERATION DE TOUS LES TYPES D'ARTICLES   //
		$this->loadModel('PostsType');
		$sitemaps['postsTypes'] = $this->PostsType->find(
			array(
				'fields' => array('id', 'name', 'modified'),
				'order' => 'name',
				'conditions' => array('online' => 1)
			)
		);
		$this->unloadModel('PostsType');
		
		/////////////////////////////////////////////
		//   RECUPERATION DE TOUS LES REDACTEURS   //
		$this->loadModel('User');
		$sitemaps['writers'] = $this->User->find(
			array(
				'fields' => array('id', 'name'),
				'order' => 'name',
				'conditions' => array('online' => 1)
			)
		);
		$this->unloadModel('User');
		
		/////////////////////////////////////////////////////////
		//   RECUPERATION DE TOUTES LES DATES DE PUBLICATION   //
		$this->loadModel('User');
		$sitemaps['publicationDates'] = $this->Category->query("SELECT DISTINCT(STR_TO_DATE(CONCAT(YEAR(modified), '-', MONTH(modified)), '%Y-%m')) AS publication_date FROM posts", true);
		$this->unloadModel('Category');
		
		$this->set('sitemaps', $sitemaps);
	}
	
/**
 * Fonction qui va permettre l'affichage du fichier robots.txt
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 */
	function robots() {
		
		$this->layout = 'robots'; //Définition du layout à utiliser
		
	}	
}