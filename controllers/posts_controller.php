<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des posts
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
class PostsController extends AppController {  
		    
//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet l'affichage d'un post
 *
 * @param 	integer $id 		Identifiant du post à afficher
 * @param 	varchar $slug 		Url de la page
 * @param 	varchar $prefix 	Préfixe de la page
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 03/01/2012 by FI 
 * @version 0.2 - 28/02/2012 by FI - Mise en place du fil d'ariane pour les articles 
 * @version 0.3 - 09/03/2012 by FI - Mise en place du préfixe pour les url des articles 
 * @version 0.4 - 01/80/2012 by FI - Récupération des commentaires uniquement si l'option est cochée, rajout du formulaire de contact dans l'affichage
 */	
	function view($id, $slug, $prefix) {
		
		//Conditions de recherche
		$conditions = array(
			'fields' => array('id', 'name', 'short_content', 'content', 'page_title', 'page_description', 'page_keywords', 'slug', 'display_form', 'category_id', 'prefix'),
			'conditions' => array('online' => 1, 'id' => $id)
        );
		$datas['post'] = $this->Post->findFirst($conditions); //On récupère le premier élément
        
        //Si il est vide on affiche la page d'erreur
		//if(empty($datas['post'])) { $this->e404('Elément introuvable'); }
		if(empty($datas['post'])) { $this->redirect('home/e404'); }

		//Si le slug ou le prefix sont différents de ceux en base de données on va renvoyer sur la bonne page		
		if($slug != $datas['post']['slug'] || $prefix != $datas['post']['prefix']) { $this->redirect("posts/view/id:".$id."/slug:".$datas['post']['slug']."/prefix:".$datas['post']['prefix'], 301); }
        
        //////////////////////////////////////
		//   RECUPERATION DU FIL D'ARIANE   //
		$this->loadModel('Category'); //Chargement du modèle
		$datas['breadcrumbs'] = $this->Category->getPath($datas['post']['category_id']);
		
		$datas['breadcrumbsPost'][] = array(
			'id' => $datas['post']['id'],
			'slug' => $datas['post']['slug'],
			'name' => $datas['post']['name'],
			'prefix' => $datas['post']['prefix']
		);
		//////////////////////////////////////
				
		//$this->_send_mail_comments(); //Gestion du formulaire de commentaires	
		//$this->_send_mail_contact(); //Gestion du formulaire de contact	
			
		/*if(isset($datas['post']['display_form']) && $datas['post']['display_form']) {
			
			$this->loadModel('Formulaire');
			$formulaire = $this->Formulaire->findFirst(array('conditions' => array('id' => $datas['post']['display_form'])));				
			
			$formulaireDatas = $this->components['Xmlform']->format_form($formulaire['form_file']);				
			$validate = $formulaireDatas['validate'];
			$datas['formInfos'] = $formulaireDatas['formInfos'];
			$datas['formulaire'] = $formulaireDatas['formulaire'];
		
			$this->_send_mail($validate, $datas['formInfos']); //Gestion du formulaire
		}*/
				
		///////////////////////////////////////////////////
		//   RECUPERATION DES 20 DERNIERS COMMENTAIRES   //
		//if($datas['post']['display_form'] == 1) {
			
			$this->loadModel('PostsComment'); //Chargement du modèle
			$postsCommentsConditions = array('online' => 1, 'post_id' => $id); //Uniquement les éléments actifs
			$datas['postsComments'] = $this->PostsComment->find(array(
					'fields' => array('name', 'message'),
					'conditions' => $postsCommentsConditions,
					'order' => 'id DESC',
					'limit' => '0, 20'
			));
			$this->unloadModel('PostsComment'); //Déchargement du modèle
		//}
		////////////////////////////////////////////////////
		
		$this->set($datas); //On fait passer les données à la vue	
			
		if(isset($datas['post']['display_form']) && $datas['post']['display_form']) {
			
			$this->loadModel('Formulaire');
			$formulaire = $this->Formulaire->findFirst(array('conditions' => array('id' => $datas['post']['display_form'])));				
			
			$formulaireDatas = $this->components['Xmlform']->format_form($formulaire['form_file']);				
			$validate = $formulaireDatas['validate'];
			$this->set('formInfos', $formulaireDatas['formInfos']);
			$this->set('formulaire', $formulaireDatas['formulaire']);
			$this->set('formulaireHtml', $formulaireDatas['formulaireHtml']);
		
			$this->_send_mail($validate, $formulaireDatas['formInfos']); //Gestion du formulaire
		}
	}
	
//////////////////////////////////////////////////////////////////////////////////////////	
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////

	function backoffice_index() {
		
		//////////////////////////////////////////////////////
		//   RECUPERATION DES CONFIGURATIONS DES ARTICLES   //
		require_once(LIBS.DS.'config_magik.php'); 										//Import de la librairie de gestion des fichiers de configuration des posts
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'posts.ini', false, false); 		//Création d'une instance
		$postsConfigs = $cfg->keys_values();											//Récupération des configurations
		
		if($postsConfigs['order'] == 'modified') { $order = 'category_id ASC, modified DESC'; }
		else if($postsConfigs['order'] == 'order_by') { $order = 'category_id ASC, order_by ASC'; }
		
		$this->set('postsOrder', $postsConfigs['order']);
		
		$datas = parent::backoffice_index(true, null, $order);		
		
		$posts = array();
		foreach($datas['posts'] as $k => $v) { $posts[$v['category_id']][] = $v; }
		$datas['posts'] = $posts;
		$this->set($datas);
		
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	function backoffice_add() {

		$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
		
		if($this->request->data) {
		
			if($this->Post->id > 0 && $parentAdd) {
				
				$this->_save_assoc_datas($this->Post->id);	
				$this->_check_send_mail($this->request->data);							
				$this->redirect('backoffice/posts/index'); //On retourne sur la page de listing
			}
		}
		
		$this->_init_categories();
		$this->_init_posts_types();
		
		$this->loadModel('Formulaire');
		$formulaires = $this->Formulaire->findList(array('conditions' => array('online' => 1)));		
		$this->set('formulaires', $formulaires); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	function backoffice_edit($id = null) {
				
		$parentEdit = parent::backoffice_edit($id, false); //On fait appel à la fonction d'édition parente
		
		if($this->request->data) {
			
			if($parentEdit) {						
								
				$this->_save_assoc_datas($id, true);
				$this->_check_send_mail($this->request->data);		
				$this->redirect('backoffice/posts/index'); //On retourne sur la page de listing
			}
		}
		
		$this->_init_categories();
		$this->_init_posts_types();
		$this->_get_assoc_datas($id);
		
		$this->loadModel('Formulaire');
		$formulaires = $this->Formulaire->findList(array('conditions' => array('online' => 1)));		
		$this->set('formulaires', $formulaires); //On les envois à la vue
	}

/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un article on va également supprimer l'association entre l'article et les types d'article ainsi que les commentaires sur l'article 
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
			
			//Suppression de l'association entre les posts et les types de posts
			$this->loadModel('PostsPostsType'); //Chargement du modèle
			$this->PostsPostsType->deleteByName('post_id', $id);
			$this->unloadModel('PostsPostsType'); //Déchargement du modèle
			
			//Suppression des commentaires articles
			$this->loadModel('PostsComment'); //Chargement du modèle
			$this->PostsComment->deleteByName('post_id', $id);
			$this->unloadModel('PostsComment'); //Déchargement du modèle
			
			if($redirect) { $this->redirect('backoffice/posts/index'); } //On retourne sur la page de listing
			else { return true; }
		}
	}	
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet l'initialisation de la liste des catégories dans le site
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	function _init_categories() {

		$this->loadModel('Category'); //Chargement du modèle des catégories
		$categoriesList = $this->Category->getTreeList(); //On récupère les catégories
		$this->unloadModel('Category'); //Déchargement du modèle des catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des types de posts
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	function _init_posts_types() {
		
		$this->loadModel('PostsType'); //Chargement du modèle des types de posts
		$postsTypes = $this->PostsType->find(array('conditions' => array('online' => 1), 'fields' => array('id', 'name', 'column_title'))); //On récupère les types de posts		
		$this->unloadModel('PostsType'); //Déchargement du modèle des types de posts		
		$this->set('postsTypes', $postsTypes); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation des données de l'association entre le post et les types de posts
 *
 * @param	integer $postId Identifiant du post
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	function _get_assoc_datas($postId) {

		$this->loadModel('PostsPostsType'); //Chargement du modèle		
		$postsPostsTypes = $this->PostsPostsType->find(array('conditions' => array('post_id' => $postId))); //On récupère les données
		$this->unloadModel('PostsPostsType'); //Déchargement du modèle
		
		//On va les rajouter dans la variable $this->request->data
		foreach($postsPostsTypes as $k => $v) { $this->request->data['posts_type_id'][$v['posts_type_id']] = 1; }
	}
	
/**
 * Cette fonction permet la sauvegarde de l'association entre les posts et les types de posts
 *
 * @param	integer $postId 		Identifiant du post
 * @param	boolean $deleteAssoc 	Si vrai l'association entre l'utilisateur et les sites sera supprimée
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	function _save_assoc_datas($postId, $deleteAssoc = false) {
		
		$this->loadModel('PostsPostsType'); //Chargement du modèle

		if($deleteAssoc) { $this->PostsPostsType->deleteByName('post_id', $postId); }
		
		$postsTypeId = $this->request->data['posts_type_id'];
		foreach($postsTypeId as $k => $v) {
		
			if($v) {
		
				$this->PostsPostsType->save(array(
					'post_id' => $postId,
					'posts_type_id'	=> $k,
					'category_id' => $this->request->data['category_id']
				));
			}
		}
		$this->unloadModel('PostsPostsType'); //Déchargement du modèle
	}
	
/**
 * Cette fonction permet de vérifier si il faut envoyer un mail aux différents utilisateurs du site (uniquement dans le cas ou celui-ci est sécurisé)
 *
 * @param	array $datas Données de l'article
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 11/07/2012 by FI
 * @version 0.2 - 31/07/2012 by FI - Modification du test $datas['send_mail'] rajout de isset
 * @version 0.3 - 02/08/2012 by FI - Customisation du message envoyé
 */	
	function _check_send_mail($datas) {

		if(isset($datas['send_mail'])) {
		
			$session = Session::read('Backoffice');
			
			//Récupération des groupes d'utilisateurs du site courant
			$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
			$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('website_id' => $session['Websites']['current']))); //Recherche de tous les groupe
			
			//On formate les données
			$usersGroupsWebsitesList = array();
			foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; }
			
			//On va maintenant récupérer tous les utilisateurs de rôle user ayant ce groupe dans leurs données
			$this->loadModel('User'); //Chargement du modèle
			$users = $this->User->find(array('conditions' => 'users_group_id IN ('.implode(',', $usersGroupsWebsitesList).')')); //Recherche de tous les groupe
			
			//Envoi des emails
			if(count($users) > 0) {		
	
				foreach($users as $k => $v) {
					
					if(!empty($v['email'])) {
						
						$currentWebsite = Session::read('Backoffice.Websites.current'); //Récupération du site courant
						$urlWebsite = Session::read('Backoffice.Websites.details.'.$currentWebsite.'.url'); //Récupération du site courant 						
						
						$txtMails = $this->components['Text']->format_for_mailing(
							array('message_mail' => $datas['message_mail']),
							$urlWebsite
						); //On fait appel au composant Text pour formater les textes des mails
						
						$emailC = new Email();
						$mailDatas = array(
							'subject' => '::Mise à jour article::',
							'to' => $v['email'],
							'element' => 'frontoffice/email/mise_a_jour_article',
							'vars' => array('messageContent' => $txtMails['message_mail'])
						);
						$emailC->send($mailDatas, $this); //On fait appel au composant email
						unset($emailC);
					}
				}
			}
		}		
	}	
}