<?php
class SitemapsController extends AppController {
	
	public $auto_load_model = false;
		
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
	function sitemap() {
	
		$this->layout = 'sitemap'; //Définition du layout à utiliser	
	
		/////////////////////////////////////////////////
		//   RECUPERATION DES DIFFERENTES CATEGORIES   //
		$this->loadModel('Category');
		$sitemaps['categories'] = $this->Category->find(
			array(
				'order' => 'lft',
				'conditions' => 'online = 1 AND level <> 0'
			)
		);
	
		///////////////////////////////////////////
		//   RECUPERATION DE TOUS LES ARTICLES   //
		$this->loadModel('Post');
		$sitemaps['posts'] = $this->Post->find(
			array(
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
				'order' => 'name',
				'conditions' => array('online' => 1)
			)
		);
		$this->unloadModel('PostsType');
	
		/////////////////////////////////////////////
		//   RECUPERATION DE TOUS LES REDACTEURS   //
		$redacteurs = array();
		foreach($sitemaps['posts'] as $k => $v) { $redacteurs[] = $v['created_by']; }
		$sitemaps['writers'] = array();
		if(count($redacteurs) > 0) {
			
			$this->loadModel('User');
			$sitemaps['writers'] = $this->User->find(
				array(
					'order' => 'name',
					'conditions' => "id IN (".implode(',', $redacteurs).")",
				)
			);
			$this->unloadModel('User');
		}
	
		/////////////////////////////////////////////////////////
		//   RECUPERATION DE TOUTES LES DATES DE PUBLICATION   //
		$this->loadModel('User');
		$sitemaps['publicationDates'] = $this->Category->query("SELECT DISTINCT(STR_TO_DATE(CONCAT(YEAR(modified), '-', MONTH(modified)), '%Y-%m')) AS publication_date FROM posts", true);
		$this->unloadModel('Category');
	
		//Liens complémentaires en provenance d'autres plugins
		$moreLinksPath = CONFIGS.DS.'plugins'.DS.'sitemaps';
		if(is_dir($moreLinksPath)) {
		
			$moreLinks = FileAndDir::directoryContent($moreLinksPath);
			foreach($moreLinks as $moreLink) { include_once($moreLinksPath.DS.$moreLink); }
		}
		
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