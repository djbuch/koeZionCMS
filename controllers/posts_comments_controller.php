<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des commentaires sur les posts
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
class PostsCommentsController extends AppController {
	
//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction permet de récupérer le nombre de commentaires, validés, pour un post donné
 *
 * @param 	integer $postId 	Identifiant du post
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */
	function get_nb_comments($postId) {
	
		$conditions = array('online' => 1, 'post_id' => $postId);
		return $this->PostsComment->findCount($conditions);
	}	
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet l'affichage de la liste des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/04/2012 by FI
 */
	function backoffice_index() {
		
		parent::backoffice_index(false, array('id', 'name', 'online'));
	}	
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/02/2012 by FI
 */
	function backoffice_add() {
	
		parent::backoffice_add(); //On fait appel à la fonction d'ajout parente
		$this->_init_posts();
	}	
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/02/2012 by FI
 */
	function backoffice_edit($id = null) {	
	
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente
		$this->_init_posts();
	}	
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet l'initialisation de la liste des posts
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 06/02/2012 by FI
 */
	function _init_posts() {
	
		$this->loadModel('Post'); //Chargement du modèle des posts
		$posts = $this->Post->findList(array('conditions' => array('online' => 1))); //On récupère les posts
		$this->unloadModel('Post'); //Déchargement du modèle des posts
		$this->set('posts', $posts); //On les envois à la vue
	}	
}