<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des types de posts
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
class PostsTypesController extends AppController {
	
//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////		
	
/**
 * Cette fonction permet de récupérer la liste des type de post pour un post donné
 *
 * @param 	integer $postId 	Identifiant du post
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */
	function get_posts_types($postId) {
	
		$result = $this->PostsType->find(
			array(
				'fields' => array('id', 'name'),
				'conditions' => array('online' => 1),
				'moreConditions' => 'KzPostsType.id IN (SELECT posts_type_id FROM posts_posts_types WHERE post_id = '.$postId.')'
			)
		);
		return $result;
	}	
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet l'affichage de la liste des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 29/05/2012 by FI
 */
	function backoffice_index() { 
		
		$datas = parent::backoffice_index(true, array('id', 'name', 'column_title', 'online'), 'column_title, order_by, name');
		
		$postsTypes = array();
		foreach($datas['postsTypes'] as $k => $v) { $postsTypes[$v['column_title']][] = $v; }
		$datas['postsTypes'] = $postsTypes;		
		$this->set($datas);
	}
	
/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un article on va également supprimer les associations
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 26/05/2012 by FI
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
	
			//Suppression de l'association entre les posts et les types de posts
			$this->loadModel('PostsPostsType'); //Chargement du modèle
			$this->PostsPostsType->deleteByName('posts_type_id', $id);
			$this->unloadModel('PostsPostsType'); //Déchargement du modèle
			
			if($redirect) { $this->redirect('backoffice/posts_types/index'); } //On retourne sur la page de listing
			else { return true; }
		}
	}
    
/**
 * Cette fonction permet l'initialisation pour la suppression des fichier de cache
 * 
 * @param	array	$params Paramètres éventuels
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 20/12/2012 by FI
 */  
	protected function _init_caching($params = null) {	
		
		$this->cachingFiles = array(		
			TMP.DS.'cache'.DS.'variables'.DS.'PostsTypes'.DS."home_page_website_".CURRENT_WEBSITE_ID.'.cache',
			TMP.DS.'cache'.DS.'variables'.DS.'PostsTypes'.DS."website_".CURRENT_WEBSITE_ID.'.cache'
		);		
	}	
}