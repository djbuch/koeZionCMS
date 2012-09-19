<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des pages (menus, pages standard, pages contact) du site
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
class CategoriesController extends AppController {   

//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction permet l'affichage d'une page
 *
 * @param 	integer $id 	Identifiant de la page à afficher
 * @param 	varchar $slug 	Url de la page
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 03/01/2012 by FI 
 * @version 0.2 - 23/02/2012 by FI - Mise en place de la sélection des frères pour affichage dans la colonne de droite
 * @version 0.3 - 25/02/2012 by FI - Mise en place de la redirection vers une autre catégorie
 * @version 0.4 - 25/02/2012 by FI - Mise en place de la gestion d'un titre pour la colonne de droite lors de l'affichage des catégories "frères"
 * @version 0.5 - 28/02/2012 by FI - Modification de l'affichage du détail d'une catégorie, mise en place de la variable is_full_page calculée en fonction des éléments de la colonne de droite
 * @version 0.6 - 01/03/2012 by FI - Rajout de la gestion du formulaire de contact dans la page catégorie
 * @version 0.7 - 06/03/2012 by FI - récupération de la liste des rédacteurs
 * @version 0.8 - 12/03/2012 by FI - Modification de la récupération des types d'articles 
 * @version 0.9 - 12/04/2012 by FI - Mise en place de l'internationnalisation 
 * @version 1.0 - 26/04/2012 by FI - Rajout d'un mot de passe pour consulter la page
 * @version 1.1 - 22/05/2012 by FI - Mise en place de la gestion des filtres des articles
 * @version 1.2 - 05/06/2012 by FI - Modification de la gestion de la sécurité de la page
 * @version 1.3 - 16/07/2012 by FI - Rajout de la requête pour la récupération des produits
 * @version 1.4 - 02/08/2012 by FI - Passage de la gestion du formulaire de contact dans une fonction pour le mutualiser avec d'autres contrôleurs
 */	
	function view($id, $slug) {
	
		//Session::delete('Frontoffice.Category');
	
		///////////////////////////////////////////////
		//   RECUPERATION DE LA CATEGORIE DEMANDEE   //
		$conditions = array(
			'fields' => array(
				'id', 
				'name', 
				'page_title', 
				'page_description', 
				'page_keywords', 
				'slug', 
				'content', 
				'type', 
				'display_brothers', 
				'title_brothers', 
				'display_children', 
				'title_children', 
				'parent_id', 
				'redirect_category_id', 
				'level', 
				'display_form', 
				'is_secure', 
				'txt_secure', 
				'title_posts_list'
			),
			'conditions' => array('online' => 1, 'id' => $id)
        );
		$datas['category'] = $this->Category->findFirst($conditions);		
		///////////////////////////////////////////////
		
		////////////////////////////////////////////////////////////////////////////////////
		//   GESTION DES EVENTUELLES ERREURS DANS LE RETOUR DE LA REQUETE OU DANS L'URL   //
		//Si le tableau de retour de la bdd est vide on va afficher une erreur 404 car aucun élément ne correspond
		//if(empty($datas['category'])) { $this->e404('Page introuvable'); }
		if(empty($datas['category'])) { $this->redirect('home/e404'); }

		//Si l'url est différente de celle en base de données on va renvoyer sur la bonne page
		if($slug != $datas['category']['slug']) { $this->redirect("categories/view/id:$id/slug:".$datas['category']['slug'], 301); }
		////////////////////////////////////////////////////////////////////////////////////
		
		////////////////////////////////////////////////////////////
		//   GESTION DE LA REDIRECTION VERS UNE AUTRE CATEGORIE   //
		if($datas['category']['redirect_category_id'] != 0) {
			
			//Cas particulier la redirection vers la home page
			if($datas['category']['redirect_category_id'] == -1) { $redirectUrl = '/'; } 
			else {				
			
				$redirectId = $datas['category']['redirect_category_id']; //Identifiant de la catégorie de redirection
				$redirectConditions = array('fields' => array('slug'), 'conditions' => array('id' => $redirectId)); //Conditions de recherche
				$redirectCategory = $this->Category->findFirst($redirectConditions); //Récupération des données de la catégorie
				$redirectSlug = $redirectCategory['slug']; //Récupération du slug
				$redirectUrl = "categories/view/id:$redirectId/slug:".$redirectSlug; //On lance la redirection
			}
			
			$this->redirect($redirectUrl, 301); //On lance la redirection
		}		
		////////////////////////////////////////////////////////////
		
		$datas['breadcrumbs'] = $this->Category->getPath($id); //Récupération du fil d'ariane
		
		//Récupération de l'éventuelle données permettant de savoir si la page est visible (dans la variable de session)
		$isAuthCategory = Session::read('Frontoffice.Category.'.$datas['category']['id'].'.isAuth');
		
		//Si la page est sécurisée il va falloir vérifier si l'utilisateur ne s'est pas déjà connecté
		if(isset($datas['category']['is_secure']) && $datas['category']['is_secure'] && !$isAuthCategory) {
			
			///////////////////////////////
			//   GESTION DU FORMULAIRE   //
			if(isset($this->request->data['formulaire_secure'])) { //Si le formulaire de contact est posté
			
				$data = $this->request->data; //Mise en variable des données postées			
				$data['password'] = sha1($data['password']); //Cryptage du mot de passe
			
				//Récupération du login et du mot de passe dans des variables
				$postLogin = $data['login'];
				$postPassword = $data['password'];
				
				//Récupération de l'utilisateur
				$this->loadModel('User');
				$user = $this->User->findFirst(array('conditions' => array('login' => $postLogin)));				
								
				//Si on récupère un utilisateur
				if(!empty($user)) {					
					
					//Récupération des données de l'utilisateur dans des variables
					$bddPassword = $user['password'];
					$bddRole = $user['role'];
					$bddOnline = $user['online'];
					
					//On va contrôler que le mot de passe saisi soit identique à celui en base de données
					if($postPassword == $bddPassword) {
					
						//Ensuite on contrôle que cet utilisateur à bien le droit de se connecter au backoffice
						if($bddOnline) {
					
							if($bddRole == 'user') {
					
								//Récupération des sites auxquels l'utilisateurs peut se connecter
								$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
								$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
					
								//On check qu'il y ait au moins un site
								if(count($usersGroupsWebsites) > 0) {
					
									//On récupère la liste des sites dans un tableau
									$usersGroupsWebsitesList = array();
									foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; }
					
									if(in_array(CURRENT_WEBSITE_ID, $usersGroupsWebsitesList)) { 
					
										Session::write('Frontoffice.Category.'.$datas['category']['id'].'.isAuth', true);
										$this->redirect("categories/view/id:$id/slug:".$datas['category']['slug'], 301);
									} else { //Gestion des erreurs					
					
										$message = '<p class="error">Vous ne disposez pas des droits nécessaires pour accéder à cette page (CAS 2)</p>';
										$this->set('message', $message);
									}
								} else { //Gestion des erreurs					
					
									$message = '<p class="error">Vous ne disposez pas des droits nécessaires pour accéder à cette page (CAS 1)</p>';
									$this->set('message', $message); 
								}
							} else { //Gestion des erreurs					
					
								$message = '<p class="error">Désolé mais votre typologie ne vous donne pas accès à cette page</p>';
								$this->set('message', $message);								
							}
						} else { //Gestion des erreurs					
					
							$message = '<p class="error">Désolé mais votre accès n\'est pas autorisé</p>';
							$this->set('message', $message); 
						}					
					} else { //Gestion des erreurs					
					
						$message = '<p class="error">Désolé mais le mot de passe ne concorde pas</p>';
						$this->set('message', $message); 
					}
				} else { //Gestion des erreurs					
					
					$message = '<p class="error">Désolé aucun utilisateur n\'a été trouvé</p>';
					$this->set('message', $message);					
				}
			}
			//////////////////////////////////////////			
			
			$this->set($datas); //On fait passer les données à la vue
			$this->render('/categories/not_auth');
			
		} else {
			
			$datas['is_full_page'] = 1; //Par défaut on affichera le détail de la catégorie en pleine page			
			
			//////////////////////////////////
			//   RECUPERATION DES ENFANTS   //
			if($datas['category']['display_children']) { //Si on doit récupérer les enfants
			
				$datas['children'] = $this->Category->getChildren($datas['category']['id'], false, false, $datas['category']['level']+1, array('conditions' => array('online' => 1))); //On récupère les enfants de la catégorie parente
				$datas['is_full_page'] = 0; //Si on doit afficher les catégories filles alors il faut la colonne de droite
			}
			
			/////////////////////////////////
			//   RECUPERATION DES FRERES   //
			if($datas['category']['display_brothers']) { //Si on doit récupérer les frères
			
				$brothers = $this->Category->getChildren($datas['category']['parent_id'], false, false, $datas['category']['level'], array('conditions' => array('online' => 1))); //On récupère les enfants de la catégorie parente
				
				//Cas particulier pour les catégories "frères" le titre de la colonne de droite peut varier en fonction des besoins
				//On va donc parcourir le résultat et réorganiser le tout
				foreach($brothers as $k => $v) { $datas['brothers'][$v['title_brothers']][] = $v; }				
				$datas['is_full_page'] = 0; //Si on doit afficher les catégories "frères" alors il faut la colonne de droite
			}
			
			////////////////////////////////////////////
			//   GESTION DES ARTICLES ET CATALOGUES   //
			
			//On va compter le nombre d'articles de cette catégorie			
			$this->loadModel('Post');
			$postsConditions = array('online' => 1, 'category_id' => $id);
			$nbPosts = $this->Post->findCount($postsConditions);
			
			$this->loadModel('Catalogue'); //Chargement du model
			$cataloguesConditions = array('online' => 1, 'category_id' => $id); //Conditions de recherche par défaut
			$nbCatalogues = $this->Catalogue->findCount($cataloguesConditions);
			
			if($nbPosts > 0) {
				
				//////////////////////////////////////////////////////
				//   RECUPERATION DES CONFIGURATIONS DES ARTICLES   //
				require_once(LIBS.DS.'config_magik.php'); 										//Import de la librairie de gestion des fichiers de configuration des posts
				$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'posts.ini', false, false); 		//Création d'une instance
				$postsConfigs = $cfg->keys_values();											//Récupération des configurations
				//////////////////////////////////////////////////////
				
				$datas['displayPosts'] = true;
				
				//Récupération des types d'articles
				$this->loadModel('PostsType');
				$datas['postsTypes'] = $this->PostsType->get_for_front($id);
								
				//Construction des paramètres de la requête
				$postsQuery = array(
					'conditions' => $postsConditions,
					'fields' => array('id', 'name', 'short_content', 'slug', 'display_link', 'modified_by', 'modified, prefix', 'category_id'),
					'limit' => $this->pager['limit'].', '.$this->pager['elementsPerPage']
				);				
				
				if($postsConfigs['order'] == 'modified') { $postsQuery['order'] = 'modified DESC'; }
				else if($postsConfigs['order'] == 'order_by') { $postsQuery['order'] = 'order_by ASC'; }		
				
				$postsQuery['moreConditions'] = ''; //Par défaut pas de conditions de recherche complémentaire
						
				$datas['libellePage'] = $datas['category']['title_posts_list'];			
				
				//////////////////////////////////////////////////////////////////////////
				///  GESTION DES EVENTUELS PARAMETRES PASSES EN GET PAR L'UTILISATEUR   //			
				$filterPosts = $this->_filter_posts($datas['postsTypes'], $postsConfigs['search']);
				if(isset($filterPosts['moreConditions'])) { 
					
					$postsQuery['moreConditions'] = $filterPosts['moreConditions']; 
					unset($filterPosts['moreConditions']);
				}
				
				$datas = am($datas, $filterPosts);			
				//////////////////////////////////////////////////////////////////////////
				
				$datas['posts'] = $this->Post->find($postsQuery); //Récupération des articles
				
				//On va compter le nombre d'élement de la catégorie
				//On compte deux fois le nombre de post une fois en totalité une fois en rajoutant si il est renseigné le type d'article
				//Car si on ne faisait pas cela on avait toujours la zone d'affichage des catégories qui s'affichaient lorsqu'on affichait les frères
				//même si il n'y avait pas de post
				$nbPostsCategory = $this->Post->findCount($postsConditions);
				
				$this->pager['totalElements'] = $this->Post->findCount($postsConditions, $postsQuery['moreConditions']); //On va compter le nombre d'élement
				$this->pager['totalPages'] = ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); //On va compter le nombre de page
		
				if($this->pager['totalElements'] > 0 || count($datas['postsTypes']) > 0) { $datas['is_full_page'] = 0; } //Si on doit afficher les articles alors il faut la colonne de droite
							
			} else if($nbCatalogues > 0) {			
			
				$datas['displayCatalogues'] = true;
				$this->pager['elementsPerPage'] = 30;
				
				/////////////////////////////////////
				//   RECUPERATION DES CATALOGUES   //				
				//Définition des tris
				$defaultOrder = array();				
				if(isset($_GET['order'])) { 
					
					foreach($_GET['order'] as $column => $dir) { $defaultOrder[] = $column.' '.$dir; } 
				}			
				if(empty($defaultOrder)) { $defaultOrder[] = 'reference'; }
				
				//Construction des paramètres de la requête
				$cataloguesQuery = array(
					'conditions' => $cataloguesConditions,
					'limit' => $this->pager['limit'].', '.$this->pager['elementsPerPage'],
					'order' => implode(',', $defaultOrder)
				);
				$cataloguesQuery['moreConditions'] = ''; //Par défaut pas de conditions de recherche complémentaire
								
				//////////////////////////////////////////////////////////////////////////
				///  GESTION DES EVENTUELS PARAMETRES PASSES EN GET PAR L'UTILISATEUR   //
				$filterCatalogues = $this->_filter_catalogues();
								
				if(isset($filterCatalogues['moreConditions'])) {
				
					$cataloguesQuery['moreConditions'] = $filterCatalogues['moreConditions'];
					unset($filterCatalogues['moreConditions']);
				}
				
				//$datas = am($datas, $filterPosts);
				//////////////////////////////////////////////////////////////////////////
								
				$datas['catalogues'] = $this->Catalogue->find($cataloguesQuery); //Récupération des articles
							
				//On va compter le nombre d'élement de la catégorie
				//On compte deux fois le nombre de post une fois en totalité une fois en rajoutant si il est renseigné le type d'article
				//Car si on ne faisait pas cela on avait toujours la zone d'affichage des catégories qui s'affichaient lorsqu'on affichait les frères
				//même si il n'y avait pas de post
				$nbCataloguesCategory = $this->Catalogue->findCount($cataloguesConditions);
				$this->pager['totalElements'] = $this->Catalogue->findCount($cataloguesConditions, $cataloguesQuery['moreConditions']); //On va compter le nombre d'élement
				$this->pager['totalPages'] = ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); //On va compter le nombre de page
		
				$datas['is_full_page'] = 1;
				
				//Recherche du produit coup de coeur
				$coupCoeurQuery = array(
					'conditions' => array('online' => 1, 'category_id' => $id, 'is_coup_coeur' => 1),
					'order' => 'reference'
				);
				$datas['coupCoeur'] = $this->Catalogue->findFirst($coupCoeurQuery);
			}
					
			$this->set($datas); //On fait passer les données à la vue
			
			if(isset($datas['category']['display_form']) && $datas['category']['display_form']) {
				
				$this->loadModel('Formulaire');
				$formulaire = $this->Formulaire->findFirst(array('conditions' => array('id' => $datas['category']['display_form'])));				
				
				$formulaireDatas = $this->components['Xmlform']->format_form($formulaire['form_file']);
				
				$validate = $formulaireDatas['validate'];
				$this->set('formInfos', $formulaireDatas['formInfos']);
				$this->set('formulaire', $formulaireDatas['formulaire']);
				$this->set('formulaireHtml', $formulaireDatas['formulaireHtml']);
			
				$this->_send_mail($validate, $formulaireDatas['formInfos']); //Gestion du formulaire
			}	
		}
	}
	
/**
 * Cette fonction permet de récupérer les données de la catégorie nécessaire pour la mise en place du lien vers celle-ci
 *
 * @param 	integer $id 	Identifiant de la catégorie
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 24/05/2012 by FI
 */
	function get_category_link($id) {
	
		//Récupération de la catégorie
		$category = $this->Category->findFirst(array('conditions' => array('id' => $id)));
		return array('id' => $category['id'], 'name' => $category['name'], 'slug' => $category['slug']);
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
 * @version 0.2 - 21/05/2012 by FI - Rajout d'une condition sur la récupération des catégories
 */	
	function backoffice_index() {		
			
		$conditions = array(
			'conditions' => 'type != 3',
			'fields' => array('id', 'name', 'lft', 'rgt', 'level', 'online', 'type'), 
			'order' => 'lft'
		);
		$datas['categories'] = $this->Category->find($conditions);		
		$this->pager['totalElements'] = $this->Category->findCount('type != 3');				
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
		if($parentAdd) { 
			
			delete_directory_file(TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS); //On vide le dossier qui contient les fichiers en cache
			$this->_check_send_mail($this->request->data);
			$this->redirect('backoffice/categories/index'); 
		} //On retourne sur la page de listing
		
		$categoriesList = $this->Category->getTreeList(); //On récupère les catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
		
		$this->loadModel('Formulaire');
		$formulaires = $this->Formulaire->findList(array('conditions' => array('online' => 1)));		
		$this->set('formulaires', $formulaires); //On les envois à la vue
	}	
	
/**
 * Cette fonction permet l'ajout en masse d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	function backoffice_massive_add() {
		
		set_time_limit(0);
		if($this->request->data) { //Si des données sont postées
						
			$nameList = explode("\n", $this->request->data['name_list']);
			unset($this->request->data['name_list']);			
			foreach($nameList as $k => $v) {
				
				$this->request->data['name'] = $v;
				$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
			}			
			
			if($parentAdd) {
			
				delete_directory_file(TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS); //On vide le dossier qui contient les fichiers en cache
				$this->redirect('backoffice/categories/index');
			} //On retourne sur la page de listing
		}
		
		$categoriesList = $this->Category->getTreeList(); //On récupère les catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
		
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
 * @version 0.2 - 23/03/2012 by FI - Lors de la modification d'une catégorie, si le champ online de celle-ci est égal à 0 on va mettre à jour l'ensemble des champs online des catégories filles
 */	
	function backoffice_edit($id) {
			
		$parentEdit = parent::backoffice_edit($id, false); //On fait appel à la fonction d'édition parente
			
		if($parentEdit) { 
			
			$this->_update_children_statut($id, $this->request->data['online']);	
			delete_directory_file(TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS); //On vide le dossier qui contient les fichiers en cache
			$this->_check_send_mail($this->request->data);
			$this->redirect('backoffice/categories/index'); //On retourne sur la page de listing 
		} 
		
		$categoriesList = $this->Category->getTreeList(); //On récupère les catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
		
		$this->loadModel('Formulaire');
		$formulaires = $this->Formulaire->findList(array('conditions' => array('online' => 1)));		
		$this->set('formulaires', $formulaires); //On les envois à la vue
	}
	
/**
 * Cette fonction permet la mise à jour du statut d'un élement directement depuis le listing
 *
 * @param 	integer $id Identifiant de l'élément dont le statut doit être modifié
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 */
	function backoffice_statut($id) {
	
		$parentStatut = parent::backoffice_statut($id, false); //On fait appel à la fonction d'édition parente
		if($parentStatut) {
		
			$vars = $this->get('vars'); //Récupération des données			
			$this->_update_children_statut($id, $vars['newOnline']);
			delete_directory_file(TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS); //On vide le dossier qui contient les fichiers en cache
			$this->redirect('backoffice/categories/index'); //On retourne sur la page de listing
		}		
	}	

/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un article on va également regénérer le menu 
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 26/05/2012 by FI
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {			
			
			delete_directory_file(TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS); //On vide le dossier qui contient les fichiers en cache			
			if($redirect) { $this->redirect('backoffice/categories/index'); } //On retourne sur la page de listing
			else { return true; }
		}
	}
	
/**
 * Cette fonction permet de monter une catégorie d'un rang
 * 
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/02/2012 by FI
 */	
	function backoffice_move2prev($id) {
					
		$this->auto_render = false; //Pas de rendu
		$this->Category->move2prev($id); //On fait appel à la fonction présente dans le model
		delete_directory_file(TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS); //On vide le dossier qui contient les fichiers en cache
		$this->redirect('backoffice/categories/index'); //On redirige vers l'index
	}

/**
 * Cette fonction permet de descendre une catégorie d'un rang
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/02/2012 by FI
 */	
	function backoffice_move2next($id) {
		
		$this->auto_render = false; //Pas de rendu
		$this->Category->move2next($id); //On fait appel à la fonction présente dans le model
		delete_directory_file(TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS); //On vide le dossier qui contient les fichiers en cache
		$this->redirect('backoffice/categories/index'); //On redirige vers l'index
	}	
	
//////////////////////////////////////////////////////////////////////////////////////
//										AJAX										//
//////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction est utilisée par l'éditeur de texte pour afficher la liste des liens disponibles
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/02/2012 by FI
 * @version 0.2 - 07/03/2012 by FI - Rajout de la récupération des types de posts
 * @version 0.3 - 14/03/2012 by FI - Rajout de la récupération des rédacteurs et des dates de parution
 * @version 0.4 - 16/05/2012 by FI - Modification de la récupération des catégories suite à la mise en place de la gestion des sites
 */	
	function backoffice_ajax_get_pages() {
				
		$this->layout = 'ajax'; //Définition du layout à utiliser
		
		///Récupération de toutes les catégories et envoi des données à la vue
		$categories = $this->Category->getTree(array('conditions' => 'type != 3'));
		$this->set('categories', $categories);
		
		//Récupération de tous les articles et envoi des données à la vue
		$this->loadModel('Post'); //Chargement du model
		$posts = $this->Post->find();
		$this->set('posts', $posts);
		$this->unloadModel('Post'); //Déchargement du model

		/*
		//Récupération de tous les types d'articles et envoi des données à la vue
		$this->loadModel('PostsType'); //Chargement du model
		$postsTypes = $this->PostsType->find();
		$this->set('postsTypes', $postsTypes);
		$this->unloadModel('PostsType'); //Déchargement du model
		
		//Récupération de tous les utilisateurs (Rédacteurs)
		$this->loadModel('User'); //Chargement du model
		$writers = $this->User->findList();
		$this->set('writers', $writers);
		$this->unloadModel('User'); //Déchargement du model
		
		//Récupération des dates de publication
		$publicationDates = $this->Category->query("SELECT DISTINCT(STR_TO_DATE(CONCAT(YEAR(modified), '-', MONTH(modified)), '%Y-%m')) AS publication_date FROM posts", true);
		$this->set('publicationDates', $publicationDates);
		*/
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Fonction permettant la mise à jour des statuts des catégories filles de la catégorie passée en paramètre
 * 
 * @param integer $id			Identifiant de la catégorie
 * @param integer $newOnline	Nouvelle valeur du champ online 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 */	
	function _update_children_statut($id, $newOnline) {
	
		//On va procéder à la mise à jour des catégories filles uniquement si le champ online est égal à 0
		if($newOnline == 0) {
	
			$categorie = $this->Category->findFirst(array('conditions' => array('id' => $id))); //Récupération des données de la catégorie éditée
			$online = $categorie['online']; //On récupère la valeur du champ online
			$lft = $categorie['lft']; //On récupère la valeur du champ lft
			$rgt = $categorie['rgt']; //On récupère la valeur du champ rgt
			$children = $this->Category->getTreeList(false, array('moreConditions' => 'lft > '.$lft.' AND rgt < '.$rgt)); //On récupère la liste des catégories filles
	
			//On ne procède à la mise à jour que si il y a des catégories filles
			if(count($children) > 0) {
	
				$childrenKeys = implode(',', array_keys($children)); //On génère une chaine qui va contenir la liste des identifiants des catégories filles à mettre à jour
				$sql = 'UPDATE '.$this->Category->table.' SET online = '.$newOnline.' WHERE id IN ('.$childrenKeys.')'; //On construit la requête à effectuer
				$this->Category->query($sql); //On lance la requête
			}
		}
	}    
    
/**
 * Cette fonction permet de récupérer les articles à afficher sur le frontoffice (Dans les contrôleurs Categories et Posts)
 *
 * @param 	array 	$postsTypes Liste des types de posts
 * @param 	varchar $searchType Type de recherche
 * @return 	array 	Configuration de la recherche
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 03/05/2012 by FI
 */       
    function _filter_posts($postsTypes, $searchType) {
    	
    	$return = array();
    	
    	//Si l'internaute à cliqué sur un type d'article (ou plusieurs)
    	if(isset($this->request->data['typepost']) && !empty($this->request->data['typepost'])) {
    	
    		/////////////////////////////////////////////
    		//   MISE EN PLACE DE LA REQUETE STRICTE   //
    		if($searchType == 'stricte') {
    	
    			$this->loadModel('PostsPostsType');
    			$typePost = explode(',', $this->request->data['typepost']); //Récupération des types de post passés en GET
    	
    			$tableAliasBase = Inflector::camelize('posts_posts_types'); //Définition de la base des alias
    			$sql =  'SELECT DISTINCT '.$tableAliasBase.'.post_id '; //Construction de la requête
    			$sql .= 'FROM posts_posts_types AS '.$tableAliasBase.' '; //Construction de la requête
    	
    			//Parcours de tous les types de posts passés en GET pour mettre en place les INNER JOIN
    			foreach($typePost as $k => $v) {
    	
    				$tableAlias = $tableAliasBase.$k; //Définition de l'alias de la table
    				$sql .= 'INNER JOIN posts_posts_types AS '.$tableAlias.' ON '.$tableAliasBase.'.post_id = '.$tableAlias.'.post_id '; //Construction de la requête
    			}
    	
    			$sql .= 'WHERE 1 '; //Construction de la requête
    	
    			//Parcours de tous les types de posts passés en GET pour mettre en place les conditions de récupération
    			foreach($typePost as $k => $v) {
    	
    				$tableAlias = $tableAliasBase.$k; //Définition de l'alias de la table
    				$sql .= ' AND '.$tableAlias.'.posts_type_id = '.$v; //Construction de la requête
    			}
    	
    	
    			$result = $this->PostsPostsType->query($sql, true);
    			$postsIdIn = array();
    			foreach($result as $k => $v) { $postsIdIn[] = $v['post_id']; }
    	
    			if(count($postsIdIn)) { $return['moreConditions'] = 'Post.id IN ('.implode(',', $postsIdIn).')'; }
    			else { $return['moreConditions'] = 'Post.id IN (0)'; }
    	
    			///////////////////////////////////////////
    			//   MISE EN PLACE DE LA REQUETE LARGE   //
    		} else if($searchType == 'large') {
    	
    			//Construction de la requête de recherche
    			$return['moreConditions'] = 'Post.id IN (SELECT post_id FROM posts_posts_types WHERE posts_type_id';
    			if(is_numeric($this->request->data['typepost'])) { $return['moreConditions'] .= ' = '.$this->request->data['typepost']; } //Si un seul type
    			else { $return['moreConditions'] .= ' IN ('.$this->request->data['typepost'].')'; }	//Si plusieurs types
    			$return['moreConditions'] .= ')';
    		}
    	
    		$typepost = $this->request->data['typepost']; //Récupération des types passés en GET
    		$libellePage = ''; //Par défaut le libellé de la page est vide
    	
    		//Parcours des types de posts
    		foreach($postsTypes as $columnTitle => $postsTypesValues) {
    	
    			$typePost = explode(',', $typepost); //On transforme les types de posts en tableau
    			foreach($postsTypesValues as $k => $v) { //On parcours les types de post
    	
    				//On stocke le libellé du type de post si celui-ci est passé en paramètre
    				if(in_array($k, $typePost)) { $libellePage[] = $v; }
    			}
    		}
    	
    		$return['libellePage'] = 'Articles de la catégorie : '.implode(', ', $libellePage); //Construction du titre de la page
    	
    		//Si l'internaute à cliqué sur un rédacteur
    	} else if(isset($this->request->data['writer']) && is_numeric($this->request->data['writer'])) {
    	
    		$return['moreConditions'] = 'modified_by = '.$this->request->data['writer'];
    	
    		//On va récupérer le libellé de l'utilisateur pour le stocker dans le libellé de la page
    		$this->loadModel('User');
    		$user = $this->User->findFirst(array('conditions' => array('id' => $this->request->data['writer'])));
    		$return['libellePage'] = "Articles rédigés par ".$user['name'];
    		$this->unloadModel('User');
    	
    		//Si l'internaute à cliqué sur une date
    	} else if(isset($this->request->data['date']) && !empty($this->request->data['date'])) {
    	
    		$date = explode('-', $this->request->data['date']); //Récupération des données sur la date
    		if(isset($date[0]) && is_numeric($date[0]) && isset($date[1]) && is_numeric($date[1])) {
    	
    			$return['moreConditions'] = 'YEAR(modified) = '.$date[0].' AND MONTH(modified) = '.$date[1];
    			$displayDate = $this->components['Text']->date_sql_to_human($this->request->data['date'].'-00');
    			$return['libellePage'] = "Articles rédigés en ".$displayDate['txt'];
    		}
    	}
    	
    	return $return;
    }

    
/**
 * Cette fonction permet de récupérer les produits à afficher sur le frontoffice (Dans les contrôleurs Categories)
 *
 * @param 	array	$request Critères de recherche
 * @return 	array 	Liste des catégories
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 20/07/2012 by FI
 */
    
    function _filter_catalogues() {
    
    	$return = array(); //Tableau retourné par la fonction
    	$request = $this->request->data; //Récupération des champs du formulaire
    
    	if(!empty($request)) {
    
    		unset($request['rechercher']); //On va en premier lieu supprimer la valeur du bouton rechercher
    		unset($request['order']);
    
    		$query = array();
    		foreach($request as $field => $fieldValue) {
    
    			if(!empty($fieldValue)) {
    				$query[] = " ".$field." LIKE '%".$fieldValue."%' ";
    			}
    		}
    
    		$return['moreConditions'] = implode('AND', $query);
    	}
    	return $return;
    }
	
/**
 * Cette fonction permet de vérifier si il faut envoyer un mail aux différents utilisateurs du site (uniquement dans le cas ou celui-ci est sécurisé)
 *
 * @param	array $datas Données de la catégorie
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 30/08/2012 by FI
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
							'subject' => '::Mise à jour catégorie::',
							'to' => $v['email'],
							'element' => 'frontoffice/email/mise_a_jour_categorie',
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