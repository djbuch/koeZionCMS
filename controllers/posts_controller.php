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
	public function view($id, $slug, $prefix) {
		
		//Conditions de recherche
		$conditions = array('conditions' => array('online' => 1, 'id' => $id));
		$datas['post'] = $this->Post->findFirst($conditions); //On récupère le premier élément
        
		//Si aucune catégorie n'est définie on lance une erreur
		if($datas['post']['category_id'] == 0) {
			
			Session::write('redirectMessage', "Désolé l'article n'existe plus");
			$this->redirect('home/e404');			
		}
		
        //Si il est vide on affiche la page d'erreur
		//if(empty($datas['post'])) { $this->e404('Elément introuvable'); }
		if(empty($datas['post'])) { 
			
			Session::write('redirectMessage', "Désolé l'article n'existe plus");
			$this->redirect('home/e404'); 
		}

		//Si le slug ou le prefix sont différents de ceux en base de données on va renvoyer sur la bonne page		
		if($slug != $datas['post']['slug'] || $prefix != $datas['post']['prefix']) { $this->redirect("posts/view/id:".$id."/slug:".$datas['post']['slug']."/prefix:".$datas['post']['prefix'], 301); }
        
        //////////////////////////////////////
		//   RECUPERATION DU FIL D'ARIANE   //
		$this->loadModel('Category'); //Chargement du modèle
		$datas['breadcrumbs'] = $this->Category->getPath($datas['post']['category_id']);
		$datas['category'] = $this->Category->findFirst(array('conditions' => array('id' => $datas['post']['category_id']))); //Récupération des données de la catégorie parente
		
		$datas['breadcrumbsPost'][] = array(
			'id' => $datas['post']['id'],
			'slug' => $datas['post']['slug'],
			'name' => $datas['post']['name'],
			'prefix' => $datas['post']['prefix']
		);
		//////////////////////////////////////
				
		///////////////////////////////////////////////////
		//   RECUPERATION DES 20 DERNIERS COMMENTAIRES   //
		//if($datas['post']['display_form'] == 1) {
			
			$this->loadModel('PostsComment'); //Chargement du modèle
			$postsCommentsConditions = array('online' => 1, 'post_id' => $id); //Uniquement les éléments actifs
			$datas['postsComments'] = $this->PostsComment->find(array(
				'conditions' => $postsCommentsConditions,
				'order' => 'id DESC',
				'limit' => '0, 20'
			));
			$this->unloadModel('PostsComment'); //Déchargement du modèle
		//}
		////////////////////////////////////////////////////
				
		$datas['children'] = array();
		$datas['brothers'] = array();
		$datas['postsTypes'] = array();
		
		//////////////////////////////////
		//   RECUPERATION DES ENFANTS   //
		$datas = $this->_get_children_category($datas);
								
		/////////////////////////////////
		//   RECUPERATION DES FRERES   //
		$datas = $this->_get_brothers_category($datas);
		
		/////////////////////////////
		//   GESTION DES BOUTONS   //
		$datas = $this->_get_right_buttons_posts($datas);	
		
		//////////////////////////////////////
		//   GESTION DES TYPES D'ARTICLES   //
		$datas = $this->_get_posts_types($datas);	
			
		$this->set($datas); //On fait passer les données à la vue	
				
		//On va tester si des données sont postées par un formulaire et que le plugin Formulaires n'est pas installé
		if(isset($this->request->data['type_formulaire']) && !isset($this->plugins['Formulaires'])) { $this->_send_mail_comments(); }
	}
	
/**
 * Cette fonction permet la diffusion automatique des articles offline ayant une date de publication renseignée et antérieure (ou égale) à la date du jour
 * 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/10/2012 by FI
 */	
	public function update_publication_date() {
		
		$this->layout = 'empty'; //Définition du layout à utiliser
		$datas['type'] = 'xml';
		$this->set($datas);
		
		require_once(LIBS.DS.'config_magik.php');
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'security_code.ini', true, false);
		$cfgValues = $cfg->keys_values();
		
		if(isset($_GET['update_code']) && !empty($_GET['update_code']) && !empty($cfgValues['security_code']) && ($_GET['update_code'] == $cfgValues['security_code'])) {

			//Conditions de recherche
			$conditions = array('conditions' => "online = 0 AND publication_date <> '0000-00-00' AND publication_date <= '".date('Y-m-d')."'");
			$post = $this->Post->find($conditions); //On récupère le premier élément
			foreach($post as $k => $v) {
				
				$v['online'] = 1;
				$this->Post->save($v);
			}			
			
			$this->set('update_result', 'MISE A JOUR EFFECTUEE');
			$this->set('update_message', "La mise à jour des dates de publications à été effectuée");
			
		} else {
			
			$this->set('update_result', 'MISE A JOUR NON EFFECTUEE');
			$this->set('update_message', "Le code d'export n'a pu être vérifié");
			
		}
	}
	
/**
 * Cette fonction est chargée de mettre en place le flux rss pour la catégorie demandée
 * 
 * @param 	integer $id 	Identifiant de la page
 * @param 	varchar $slug 	Url de la page
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 05/11/2012 by FI 
 * @version 0.2 - 30/10/2014 by FI - Déplacement de cette fonction du contrôleur Categories 
 * @see http://baptiste-wicht.developpez.com/tutoriels/php/rss/ : Pour l'exemple de la structure du fichier ainsi que les différents paramètres possibles
 * @see http://www.craym.eu/tutoriels/developpement/flux_RSS.html : A lire plus complet que le précédent
 * @see http://curul2.free.fr/style.php?feed= ; Pour rajouter un css au flux
 */	
	public function rss($id, $slug) {
		
		$this->layout = 'rss'; //Définition du layout à utiliser		
		
		$datas = $this->_get_datas_category($id);
		$datas = $this->_get_posts_category($datas, false);		
		
		$this->set($datas);
	}
	
//////////////////////////////////////////////////////////////////////////////////////////	
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet l'affichage de la liste des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */
	public function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {
		
		//////////////////////////////////////////////////////
		//   RECUPERATION DES CONFIGURATIONS DES ARTICLES   //
		require_once(LIBS.DS.'config_magik.php'); 										//Import de la librairie de gestion des fichiers de configuration des posts
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'posts.ini', false, false); 		//Création d'une instance
		$postsConfigs = $cfg->keys_values();	
		
		if($postsConfigs['order'] == 'modified') { $order = 'category_id ASC, modified DESC'; }
		else if($postsConfigs['order'] == 'created') { $order = 'category_id ASC, created_by ASC'; }
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
 * @version 0.2 - 21/06/2013 by FI - Rajout de la récupération des boutons colonnes de doite --> C'est le jour le plus long de l'année
 * @version 0.3 - 03/11/2013 by FI - Modification de la fonction de transformation des dates
 * @version 0.4 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	public function backoffice_add($redirect = true, $forceInsert = false) {

		$this->_transform_date('fr2Sql', 'publication_date'); //Transformation de la date FR en date SQL	
		$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
		
		if($this->request->data) {
		
			if($this->Post->id > 0 && $parentAdd) {		 
				
				$this->_save_assoc_datas_posts_posts_type($this->Post->id);	
				$this->_save_assoc_datas_posts_right_button($this->Post->id);	
				$this->_check_send_mail($this->request->data);	
				FileAndDir::remove(TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS.'home_page_website_'.CURRENT_WEBSITE_ID.'.cache'); //On supprime le dossier cache
				FileAndDir::remove(TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS.'website_'.CURRENT_WEBSITE_ID.'.cache'); //On supprime le dossier cache
				$this->redirect('backoffice/posts/index'); //On retourne sur la page de listing
			}
		}
		
		$this->_transform_date('sql2Fr', 'publication_date'); //Transformation de la date SQL en date FR		
		$this->_init_categories();
		$this->_init_posts_types();
		$this->_init_right_buttons();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 21/06/2013 by FI - Rajout de la récupération des boutons colonnes de doite --> C'est le jour le plus long de l'année
 * @version 0.3 - 03/11/2013 by FI - Modification de la fonction de transformation des dates
 * @version 0.4 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	public function backoffice_edit($id = null, $redirect = true) {
				
		$this->_transform_date('fr2Sql', 'publication_date'); //Transformation de la date FR en date SQL
		$parentEdit = parent::backoffice_edit($id, false); //On fait appel à la fonction d'édition parente
		
		if($this->request->data) {
			
			if($parentEdit) {						
								
				$this->_save_assoc_datas_posts_posts_type($this->Post->id, true);	
				$this->_save_assoc_datas_posts_right_button($this->Post->id, true);	
				$this->_check_send_mail($this->request->data);	
				FileAndDir::remove(TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS.'home_page_website_'.CURRENT_WEBSITE_ID.'.cache'); //On supprime le dossier cache
				FileAndDir::remove(TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS.'website_'.CURRENT_WEBSITE_ID.'.cache'); //On supprime le dossier cache
				$this->redirect('backoffice/posts/index'); //On retourne sur la page de listing
			}
		}
		
		$this->_transform_date('sql2Fr', 'publication_date'); //Transformation de la date SQL en date FR
		$this->_init_categories();
		$this->_init_posts_types();
		$this->_init_right_buttons();
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
	public function backoffice_delete($id, $redirect = true) {
	
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
	
//////////////////////////////////////////////////////////////////////////////////////
//										AJAX										//
//////////////////////////////////////////////////////////////////////////////////////	
		
/**
 * Cette fonction est utilisée lors de l'ajout d'un nouvel attribut
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/12/2012 by FI
 * @version 0.2 - 23/12/2013 by FI - Fonction déplacée dans le contrôleur App
 */
	/*public function backoffice_ajax_add_right_button($rightButtonId) {
	
		$this->layout = 'ajax'; //Définition du layout à utiliser		
				
		//Récupération des informations du bouton
		$this->loadModel('RightButton'); //Chargement du modèle
		$rightButton = $this->RightButton->findFirst(array('fields' => array('name'), 'conditions' => array('id' => $rightButtonId))); //On récupère les données
		$this->unloadModel('RightButton'); //Déchargement du modèle
		
		$this->set('rightButtonId', $rightButtonId);
		$this->set('rightButtonName', $rightButton['name']);
	}*/	
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction permet la récupération des boutons liés à la catégorie courante
 *
 * @param 	array 	$datas 		Tableau des données à passer à la vue
 * @return	array	Tableau de données à passer à la vue 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 21/06/2013 by FI
 */		
	protected function _get_right_buttons_posts($datas) {
					
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS;
		$cacheFile 		= "post_".$datas['post']['id']."_right_buttons";
		
		$rightButtonsPost = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$rightButtonsPost) {
		
			$this->loadModel('PostsRightButton');
			$this->PostsRightButton->primaryKey = 'post_id'; //Pour éviter les erreurs à l'exécution
			$rightButtonsConditions = array('post_id' => $datas['post']['id']);
			$nbRightButtons = $this->PostsRightButton->findCount($rightButtonsConditions);
			
			if($nbRightButtons) {
	
				$this->loadModel('RightButton');
				
				//récupération des données
				$rightButtonsList = $this->PostsRightButton->find(array('conditions' => $rightButtonsConditions, 'order' => 'order_by ASC'));
				foreach($rightButtonsList as $k => $rightButton) {
					
					$rightButtonsPost[$rightButton['position']][] = $this->RightButton->findFirst(array('conditions' => array('id' => $rightButton['right_button_id'])));
				}
				
				Cache::create_cache_file($cacheFolder, $cacheFile, $rightButtonsPost);
			} else { $rightButtonsPost = array(); }			
		}
		
		$datas['rightButtons'] = $rightButtonsPost;		
		return $datas;
	}	

/**
 * Cette fonction permet la récupération des types d'articles rattachés à la catégorie parente de l'article
 *
 * @param 	array 	$datas 		Tableau des données à passer à la vue
 * @return	array	Tableau de données à passer à la vue 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 22/06/2013 by FI
 */		
	public function _get_posts_types($datas) {

		if($datas['post']['display_posts_types']) {
			
			//Récupération des types d'articles
			$this->loadModel('PostsType');
			$datas['postsTypes'] = $this->PostsType->get_for_front($datas['category']['id']);	
		}
		return $datas;
	}

/**
 * Cette fonction permet la récupération des enfants de la catégorie courante
 *
 * @param 	array 	$datas Données de la page
 * @return	array	Données de la page
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 21/06/2013 by FI
 */	
	protected function _get_children_category($datas) {
		
		//////////////////////////////////
		//   RECUPERATION DES ENFANTS   //
		if($datas['post']['display_children']) { //Si on doit récupérer les enfants			
			
			$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS;
			$cacheFile 		= "post_".$datas['post']['id']."_children";
			
			$childrenCategory = Cache::exists_cache_file($cacheFolder, $cacheFile);
			
			if(!$childrenCategory) {
			
				$children = $this->Category->getChildren($datas['category']['id'], false, false, $datas['category']['level']+1, array('conditions' => array('online' => 1))); //On récupère les enfants de la catégorie parente
				
				//Cas particulier pour les catégories "frères" le titre de la colonne de droite peut varier en fonction des besoins
				//On va donc parcourir le résultat et réorganiser le tout
				foreach($children as $k => $v) { $childrenCategory[$datas['post']['title_colonne_droite']][] = $v; }
			
				Cache::create_cache_file($cacheFolder, $cacheFile, $childrenCategory);
			}
			
			$datas['children'] = $childrenCategory;
		}
		return $datas;
	}	

/**
 * Cette fonction permet la récupération des frères de la catégorie courante
 *
 * @param 	array 	$datas Données de la page
 * @return	array	Données de la page
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 21/06/2013 by FI
 */	
	protected function _get_brothers_category($datas) {
						
		/////////////////////////////////
		//   RECUPERATION DES FRERES   //
		if($datas['post']['display_brothers']) { //Si on doit récupérer les frères		
			
			$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS;
			$cacheFile 		= "post_".$datas['post']['id']."_brothers";
			
			$brothersCategory = Cache::exists_cache_file($cacheFolder, $cacheFile);
			
			if(!$brothersCategory) {
		
				$brothers = $this->Category->getChildren($datas['category']['parent_id'], false, false, $datas['category']['level'], array('conditions' => array('online' => 1))); //On récupère les enfants de la catégorie parente
			
				//Cas particulier pour les catégories "frères" le titre de la colonne de droite peut varier en fonction des besoins
				//On va donc parcourir le résultat et réorganiser le tout
				foreach($brothers as $k => $v) { $brothersCategory[$datas['post']['title_colonne_droite']][] = $v; }
				
				Cache::create_cache_file($cacheFolder, $cacheFile, $brothersCategory);
			}
			
			$datas['brothers'] = $brothersCategory;
		}
		
		return $datas;		
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des catégories dans le site
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	protected function _init_categories() {

		$this->loadModel('Category'); //Chargement du modèle des catégories
		$categoriesList = $this->Category->getTreeList(false); //On récupère les catégories
		$this->unloadModel('Category'); //Déchargement du modèle des catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des types de posts
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 * @version 0.2 - 04/12/2013 by FI - Classement des types par rubriques
 */	
	protected function _init_posts_types() {
		
		$this->loadModel('PostsType'); //Chargement du modèle des types de posts
		$postsTypesTMP = $this->PostsType->find(array('conditions' => array('online' => 1), 'fields' => array('id', 'name', 'column_title'))); //On récupère les types de posts		
		$this->unloadModel('PostsType'); //Déchargement du modèle des types de posts

		$postsTypes = array();
		foreach($postsTypesTMP as $v) { $postsTypes[$v['column_title']][] = $v; }		
		$this->set('postsTypes', $postsTypes); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation des boutons colonnes de droite
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 21/06/2013 by FI
 */	
	protected function _init_right_buttons() {		
		
		$this->loadModel('RightButton'); //Chargement du modèle des types de posts
		$rightButton = $this->RightButton->findList(array('conditions' => array('online' => 1))); //On récupère les types de posts
		$this->unloadModel('RightButton'); //Déchargement du modèle des types de posts
		$this->set('rightButton', $rightButton); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation des données de l'association entre le post et les types de posts
 *
 * @param	integer $postId Identifiant du post
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 * @version 0.2 - 21/06/2013 by FI - Rajout de la récupération des boutons colonnes de droite
 */	
	protected function _get_assoc_datas($postId) {

		$this->loadModel('PostsPostsType'); //Chargement du modèle		
		$postsPostsTypes = $this->PostsPostsType->find(array('conditions' => array('post_id' => $postId))); //On récupère les données
		$this->unloadModel('PostsPostsType'); //Déchargement du modèle
		
		//On va les rajouter dans la variable $this->request->data
		foreach($postsPostsTypes as $k => $v) { $this->request->data['posts_type_id'][$v['posts_type_id']] = 1; }

		$this->loadModel('PostsRightButton'); //Chargement du modèle		
		$rightButtons = $this->PostsRightButton->find(array('conditions' => array('post_id' => $postId), 'order' => 'order_by ASC')); //On récupère les données
		$this->unloadModel('PostsRightButton'); //Déchargement du modèle
		
		//On va les rajouter dans la variable $this->request->data
		foreach($rightButtons as $k => $v) {			
			
			$this->request->data['right_button_id'][$v['right_button_id']]['top'] = $v['position'];
			$this->request->data['right_button_id'][$v['right_button_id']]['activate'] = 1; 
		}
	}
	
/**
 * Cette fonction permet la sauvegarde de l'association entre les posts et les types de posts
 *
 * @param	integer $postId 		Identifiant du post
 * @param	boolean $deleteAssoc 	Si vrai l'association entre l'utilisateur et les sites sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	protected function _save_assoc_datas_posts_posts_type($postId, $deleteAssoc = false) {
		
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
 * Cette fonction permet la sauvegarde de l'association entre les posts et les boutons colonne de droite
 *
 * @param	integer $postId 		Identifiant du post
 * @param	boolean $deleteAssoc 	Si vrai l'association entre l'utilisateur et les sites sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 21/06/2013 by FI
 */			
	protected function _save_assoc_datas_posts_right_button($postId, $deleteAssoc = false) {	
		
		$this->loadModel('PostsRightButton'); //Chargement du modèle

		if($deleteAssoc) { $this->PostsRightButton->deleteByName('post_id', $postId); }
		
		if(isset($this->request->data['right_button_id'])) { $rightButtonIds = $this->request->data['right_button_id']; }
		else { $rightButtonIds = array(); }
		
		$order = 0;
		foreach($rightButtonIds as $rightButtonId => $rightButtonDatas) {
		
			if($rightButtonDatas['activate']) {
		
				$this->PostsRightButton->save(array(
					'post_id' => $postId,
					'right_button_id'	=> $rightButtonId,
					'position'	=> $rightButtonDatas['top'],
					'order_by' => $order
				));
				
				$order++;
			}
		}
		$this->unloadModel('PostsRightButton'); //Déchargement du modèle
		
		
	}
	
/**
 * Cette fonction permet de vérifier si il faut envoyer un mail aux différents utilisateurs du site (uniquement dans le cas ou celui-ci est sécurisé)
 *
 * @param	array $datas Données de l'article
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 11/07/2012 by FI
 * @version 0.2 - 31/07/2012 by FI - Modification du test $datas['send_mail'] rajout de isset
 * @version 0.3 - 02/08/2012 by FI - Customisation du message envoyé
 */	
	protected function _check_send_mail($datas) {

		//REPRENDRE FONCTIONNALITE PB LORS DE LA CREATION DE L'OBJET EMAIL
		/*if(isset($datas['send_mail'])) {
		
			$session = Session::read('Backoffice');
			
			//Récupération des groupes d'utilisateurs du site courant
			$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
			$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('website_id' => $session['Websites']['current']))); //Recherche de tous les groupe
			
			//On formate les données
			$usersGroupsWebsitesList = array();
			foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['users_group_id']; }
			
			//On va maintenant récupérer tous les utilisateurs de rôle user ayant ce groupe dans leurs données
			$this->loadModel('User'); //Chargement du modèle
			$users = $this->User->find(array('conditions' => 'users_group_id IN ('.implode(',', $usersGroupsWebsitesList).')')); //Recherche de tous les groupe
			
			//Envoi des emails
			if(count($users) > 0) {		
	
				foreach($users as $k => $v) {
					
					if(!empty($v['email'])) {
						
						$currentWebsite = Session::read('Backoffice.Websites.current'); //Récupération du site courant
						$urlWebsite = Session::read('Backoffice.Websites.details.'.$currentWebsite.'.url'); //Récupération du site courant 						
						
						$txtMails = $this->components['Email']->replace_links(
							array('message_mail' => $datas['message_mail']),
							$urlWebsite
						); //On fait appel au composant Email pour formater les textes des mails
						
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
		}*/		
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
			TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS."home_page_website_".CURRENT_WEBSITE_ID.'.cache',
			TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS."website_".CURRENT_WEBSITE_ID.'.cache'
		);
		
		if(isset($this->request->data['id']) && !empty($this->request->data['id'])) {
			
			$this->cachingFiles = array(
				TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS."post_".$this->request->data['id'].'_brothers.cache',
				TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS."post_".$this->request->data['id'].'_children.cache',
				TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS."post_".$this->request->data['id'].'_right_buttons.cache'
			);
		}
	}
}