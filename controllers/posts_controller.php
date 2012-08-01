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
			'fields' => array('id', 'name', 'short_content', 'content', 'page_title', 'page_description', 'page_keywords', 'slug', 'code', 'display_form', 'category_id', 'prefix'),
			'conditions' => array('online' => 1, 'id' => $id)
        );
		$datas['post'] = $this->Post->findFirst($conditions); //On récupère le premier élément
        
        //Si il est vide on affiche la page d'erreur
		if(empty($datas['post'])) { $this->e404('Elément introuvable'); }

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
		
		//////////////////////////////////////////
		//   GESTION DU DEPOT DE COMMENTAIRES   //
		if(isset($this->request->data['formulaire_commentaires'])) {
			
			$this->loadModel('PostsComment'); //Chargement du modèle
			if($this->PostsComment->validates($this->request->data)) { //Si elles sont valides		
				
				///////////////////////
				//   ENVOI DE MAIL   //
				$mailDatas = array(
					'subject' => '::Commentaire::',
					'to' => $this->request->data['email'],
					'element' => 'frontoffice/email/commentaire'
				);
				$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
				///////////////////////				
				
				$this->PostsComment->save($this->request->data);
				$message = '<p class="confirmation">Votre commentaire a bien été prise en compte, il sera diffusé après validation par notre modérateur</p>';
				$this->set('message', $message);
				$this->request->data = false;
			} else {
		
				$message = '<p class="error">Merci de corriger vos informations';
				foreach($this->PostsComment->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';
				$this->set('message', $message);
			}
			$this->unloadModel('PostsComment'); //Déchargement du modèle
		}
		//////////////////////////////////////////
				
		//////////////////////////////////////////
		//   GESTION DU FORMULAIRE DE CONTACT   //
		if(isset($this->request->data['formulaire_contact'])) { //Si le formulaire de contact est posté
		
			$this->loadModel('Contact');
			if($this->Contact->validates($this->request->data)) { //Si elles sont valides
		
				///////////////////////
				//   ENVOI DE MAIL   //
				$mailDatas = array(
					'subject' => '::Contact::',
					'to' => $this->request->data['email'],
					'element' => 'frontoffice/email/contact'
				);
				$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
				///////////////////////
		
				$this->Contact->save($this->request->data); //On procède à la sauvegarde des données
				$message = '<p class="confirmation">Votre demande a bien été prise en compte</p>';
				$this->set('message', $message);
				$this->request->data = false;
		
			} else {
		
				//Gestion des erreurs
				$message = '<p class="error">Merci de corriger vos informations';
				foreach($this->Contact->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';
				$this->set('message', $message);
			}
		}
		//////////////////////////////////////////
				
		///////////////////////////////////////////////////
		//   RECUPERATION DES 20 DERNIERS COMMENTAIRES   //
		if($datas['post']['display_form'] == 1) {
			
			$this->loadModel('PostsComment'); //Chargement du modèle
			$postsCommentsConditions = array('online' => 1, 'post_id' => $id); //Uniquement les éléments actifs
			$datas['postsComments'] = $this->PostsComment->find(array(
					'fields' => array('name', 'message'),
					'conditions' => $postsCommentsConditions,
					'order' => 'id DESC',
					'limit' => '0, 20'
			));
			$this->unloadModel('PostsComment'); //Déchargement du modèle
		}
		////////////////////////////////////////////////////
		
		$this->set($datas); //On fait passer les données à la vue
	}
	
//////////////////////////////////////////////////////////////////////////////////////////	
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////
	
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
 * Cette fonction permet de vérifier si il faut envoyer un mail aux différents utilisateurs du site (uniquement dans le cas ou celui-ci est sécurisé
 *
 * @param	array $datas Données de l'article
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 11/07/2012 by FI
 * @version 0.2 - 31/07/2012 by FI - Modification du test $datas['send_mail'] rajout de isset
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
					
					$emailC = new Email();
					$mailDatas = array(
						'subject' => '::Mise à jour article::',
						'to' => $v['email'],
						'element' => 'frontoffice/email/mise_a_jour_article'
					);
					$emailC->send($mailDatas, $this); //On fait appel au composant email
					unset($emailC);
				}
			}
		}		
	}	
	
	function backoffice_migration() {
		
		$this->auto_render = false; //Pas de rendu
		set_time_limit(0);
		////////////////////////////////
		//Migration des types de posts//
		/*$this->loadModel('PostsType');
		$sql = "SELECT * FROM old_posts_types";
		foreach($this->PostsType->query($sql, true) as $k => $v) { $this->PostsType->save($v, true); }
		$this->unloadModel('PostsType');*/
		
		/////////////////////////
		//Migration des sliders//
		/*$this->loadModel('Slider');
		$sql = "SELECT * FROM old_sliders";
		foreach($this->Slider->query($sql, true) as $k => $v) { 
			
			$img_name = $v['img_name'];
			$img_path = $v['img_path'];			
			$v['image'] = '<img alt="" src="'.str_replace('/upload', '/upload/images', $v['img_path']).$v['img_name'].'" style="width: 918px; height: 350px;" />';
			unset($v['img_path']);
			unset($v['img_path']);
			$this->Slider->save($v, true); 
		}
		$this->unloadModel('Slider');*/		
		
		//////////////////////////////
		//Migration des utilisateurs//
		/*$this->loadModel('User');
		$sql = "SELECT * FROM old_users";
		foreach($this->User->query($sql, true) as $k => $v) { $this->User->save($v, true); }
		$this->unloadModel('User');*/
		
		//////////////////////////
		//Migration des contacts//
		/*$this->loadModel('Contact');
		$sql = "SELECT * FROM old_contacts";
		foreach($this->Contact->query($sql, true) as $k => $v) { $this->Contact->save($v, true); }
		$this->unloadModel('Contact');*/
		
		////////////////////////////////////////////////////////////////////
		//Migration de l'association entre les posts et les types de posts//
		/*$this->loadModel('PostsPostsType');
		$sql = "SELECT * FROM old_posts_posts_types";
		foreach($this->PostsPostsType->query($sql, true) as $k => $v) { $this->PostsPostsType->save($v, true); }
		$this->unloadModel('PostsPostsType');*/
		
		///////////////////////
		//Migration des posts//
		/*$this->loadModel('Post');
		$sql = "SELECT * FROM old_posts";
		foreach($this->Post->query($sql, true) as $k => $v) { 
			
			$v['page_title'] = $v['name'];
			$v['order_by'] = $v['rang'];
			unset($v['rang']);
			pr($v);
			$this->Post->save($v, true); 
		}
		$this->unloadModel('Post');*/
		
		////////////////////////////
		//Migration des catégories//
		/*$this->loadModel('Category');
		$sql = "SELECT * FROM old_categories";
		foreach($this->Category->query($sql, true) as $k => $v) { 
			
			$v['page_title'] = $v['name'];
			pr($v);
			
			//$this->Category->add($v, true); 
			$sql = "
				INSERT INTO `categories` (`id`, `name`, `content`, `slug`, `page_title`, `page_description`, `page_keywords`, `title_children`, `title_brothers`, `title_posts_list`, `type`, `display_contact_form`, `display_children`, `display_brothers`, `is_secure`, `txt_secure`, `online`, `lft`, `rgt`, `level`, `parent_id`, `redirect_category_id`, `website_id`) 
				VALUES (
					".$v['id'].", 
					'".addslashes($v['name'])."', 
					'".addslashes($v['content'])."', 
					'".addslashes($v['slug'])."', 
					'".addslashes($v['name'])."', 
					'".addslashes($v['page_description'])."', 
					'".addslashes($v['page_keywords'])."', 
					'', 
					'".addslashes($v['title_brothers'])."', 
					'', 
					'".$v['type']."', 
					'".$v['display_contact_form']."', 
					'', 
					'".$v['display_brothers']."', 
					'', 
					'', 
					'".$v['online']."', 
					'".$v['lft']."', 
					'".$v['rgt']."', 
					'".$v['level']."', 
					'".$v['parent_id']."', 
					'".$v['redirect_category_id']."', 
					'1'
				);";
			
			pr($v);
			//$this->Category->query($sql);
		}
		$this->unloadModel('Category');*/
	}
	
}