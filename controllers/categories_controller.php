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
 * @version 1.5 - 02/10/2012 by FI - Mise en place d'un slider pour les catégories, Mise en place de la possibilité de changer le template des pages
 * @version 1.6 - 05/11/2012 by FI - Mise en fonction privée de la récupération de la catégorie ainsi que la récupération des articles associés pour pouvoir l'utiliser dans le flux rss
 */	
	public function view($id, $slug) {
	
		//Session::delete('Frontoffice.Category');
	
		///////////////////////////////////////////////
		//   RECUPERATION DE LA CATEGORIE DEMANDEE   //	
		///////////////////////////////////////////////
		$datas = $this->_get_datas_category($id);
		
		////////////////////////////////////////////////////////////////////////////////////
		//   GESTION DES EVENTUELLES ERREURS DANS LE RETOUR DE LA REQUETE OU DANS L'URL   //
		//Si le tableau de retour de la bdd est vide on va afficher une erreur 404 car aucun élément ne correspond
		//if(empty($datas['category'])) { $this->e404('Page introuvable'); }
		if(empty($datas['category'])) { 
			
			Session::write('redirectMessage', "La catégorie est vide");
			$this->redirect('home/e404'); 
		}

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
		
		/////////////////////////////////////////////////////////////////
		//   TEST POUR SAVOIR SI UN TEMPLATE PARTICULIER EST DEMANDE   //
		if($datas['category']['template_id']) {
			
			$controllerVars = $this->get('vars'); //Récupération des données du controller
			$websiteParams = $controllerVars['websiteParams']; //Récupération des données concernants le site courant
			$websiteParams['tpl_layout'] = $datas['category']['tpl_layout']; //Mise à jour du layout
			$websiteParams['tpl_code'] = $datas['category']['tpl_code']; //Mise à jour du code du layout
			$websiteParams['template_id'] = $datas['category']['template_id']; //Mise à jour de l'identifiant du template
			$this->layout = $datas['category']['tpl_layout'];
			$this->set('websiteParams', $websiteParams); //Mise à jour des données du site
		}		
		/////////////////////////////////////////////////////////////////
		
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
						
			//$datas['is_full_page'] = 1; //Par défaut on affichera le détail de la catégorie en pleine page

			$datas['children'] = array();
			$datas['brothers'] = array();
			$datas['postsTypes'] = array();
			
			//////////////////////////////////
			//   RECUPERATION DES ENFANTS   //
			$datas = $this->_get_children_category($datas);
									
			/////////////////////////////////
			//   RECUPERATION DES FRERES   //
			$datas = $this->_get_brothers_category($datas);
			
			//////////////////////////////
			//   GESTION DES ARTICLES   //
			$datas = $this->_get_posts_category($datas);
			
			//////////////////////////////
			//   GESTION DES BOUTONS   //
			$datas = $this->_get_right_buttons_category($datas);			
			
			$this->set($datas); //On fait passer les données à la vue
				
			//On va tester si des données sont postées par un formulaire et que le plugin Formulaires n'est pas installé
			if(isset($this->request->data['type_formulaire']) && !isset($this->plugins['Formulaires'])) { $this->_send_mail_contact(); }
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
	public function get_category_link($id) {
	
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
 * @version 0.3 - 22/07/2014 by FI - Suppression des champs (on récupère tous les champs)
 * @version 0.4 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	public function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {		
				
		$conditions = array('conditions' => 'type != 3', 'order' => 'lft');		
		
		if(isset($this->request->data['Search'])) {
		
			$searchConditions = array();
			foreach($this->request->data['Search'] as $k => $v) {
		
				if(trim($v) != "") { //Système de poulie lié au fait que empty(0) retourne faux
		
					if($k == 'id') { $searchConditions[] = $k."='".$v."'"; }
					else { $searchConditions[] = $k." LIKE '%".$v."%'"; }
				}
			}
		
			if(count($searchConditions) > 0) { $conditions['conditions'] .= " AND ".implode(' AND ', $searchConditions); }
		}
		
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
 * @version 0.2 - 02/10/2012 by FI - Gestion de la personnalisation des templetes par pages
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	public function backoffice_add($redirect = true, $forceInsert = false) {
		
		$this->_init_datas();
		
		$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
		if($parentAdd) { 
			
			//$this->_update_template($this->Category->id, $this->request->data['template_id']);
			$this->_save_assoc_datas($this->Category->id);
			$this->_check_send_mail($this->request->data);
			$this->redirect('backoffice/categories/index'); 
		} //On retourne sur la page de listing
	}	
	
/**
 * Cette fonction permet l'ajout en masse d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	public function backoffice_massive_add() {
		
		$this->_init_datas();
		
		set_time_limit(0);
		if($this->request->data) { //Si des données sont postées
						
			$nameList = explode("\n", $this->request->data['name_list']);
			unset($this->request->data['name_list']);			
			foreach($nameList as $k => $v) {
				
				$this->request->data['name'] = $v;
				$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
			}			
			
			if($parentAdd) { $this->redirect('backoffice/categories/index'); } //On retourne sur la page de listing
		}
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 23/03/2012 by FI - Lors de la modification d'une catégorie, si le champ online de celle-ci est égal à 0 on va mettre à jour l'ensemble des champs online des catégories filles
 * @version 0.2 - 02/10/2012 by FI - Gestion de la personnalisation des templetes par pages
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	public function backoffice_edit($id, $redirect = true) {
		
		$this->_init_datas();
			
		$parentEdit = parent::backoffice_edit($id, false); //On fait appel à la fonction d'édition parente
			
		if($parentEdit) { 
			
			$this->_update_children_statut($id, $this->request->data['online']);	
			//$this->_update_template($this->Category->id, $this->request->data['template_id']);
			$this->_save_assoc_datas($id, true);
			$this->_check_send_mail($this->request->data);
			$this->redirect('backoffice/categories/index'); //On retourne sur la page de listing 
		} 
		$this->_get_assoc_datas($id);
	}
	
/**
 * Cette fonction permet la mise à jour du statut d'un élement directement depuis le listing
 *
 * @param 	integer $id Identifiant de l'élément dont le statut doit être modifié
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */
	public function backoffice_statut($id, $redirect = true) {
	
		$parentStatut = parent::backoffice_statut($id, false); //On fait appel à la fonction d'édition parente
		if($parentStatut) {
		
			$vars = $this->get('vars'); //Récupération des données			
			$this->_update_children_statut($id, $vars['newOnline']);
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
	public function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {			
			
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
	public function backoffice_move2prev($id) {
					
		$this->auto_render = false; //Pas de rendu
		$this->Category->move2prev($id); //On fait appel à la fonction présente dans le model
		
		$this->_check_cache_configs();
		$this->_delete_cache();
		
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
	public function backoffice_move2next($id) {
		
		$this->auto_render = false; //Pas de rendu
		$this->Category->move2next($id); //On fait appel à la fonction présente dans le model
		
		$this->_check_cache_configs();
		$this->_delete_cache();
		
		$this->redirect('backoffice/categories/index'); //On redirige vers l'index
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
 * Fonction permettant la mise à jour des statuts des catégories filles de la catégorie passée en paramètre
 * 
 * @param 	integer $id			Identifiant de la catégorie
 * @param 	integer $newOnline	Nouvelle valeur du champ online 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 */	
	protected function _update_children_statut($id, $newOnline) {
	
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
 * Cette fonction permet la sauvegarde de l'association entre les catégories et les boutons
 *
 * @param	integer $categoryId		Identifiant de la catégorie
 * @param	boolean $deleteAssoc 	Si vrai l'association entre l'utilisateur et les sites sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 * @version 0.2 - 22/07/2014 by FI - Modification de la gestion de l'insertion des boutons colonne de droite dans les catégories
 */	
	protected function _save_assoc_datas($categoryId, $deleteAssoc = false) {
		
		$this->loadModel('CategoriesRightButton'); //Chargement du modèle

		if($deleteAssoc) { $this->CategoriesRightButton->deleteByName('category_id', $categoryId); }
		
		if(isset($this->request->data['right_button_id'])) { $rightButtonIds = $this->request->data['right_button_id']; }
		else { $rightButtonIds = array(); }
		
		$order = 0;
		foreach($rightButtonIds as $rightButtonId => $rightButtonDatas) {
		
			if($rightButtonDatas['activate']) {
		
				$this->CategoriesRightButton->save(array(
					'category_id' => $categoryId,
					'right_button_id'	=> $rightButtonId,
					'position'	=> $rightButtonDatas['top'],
					'order_by' => $order
				));
				
				$order++;
			}
		}
		$this->unloadModel('CategoriesRightButton'); //Déchargement du modèle
	}    
	
/**
 * Cette fonction permet l'initialisation des données de l'association entre la catégorie et les boutons
 *
 * @param	integer $categoryId Identifiant de la catégorie
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	protected function _get_assoc_datas($categoryId) {

		$this->loadModel('CategoriesRightButton'); //Chargement du modèle		
		$rightButtons = $this->CategoriesRightButton->find(array('conditions' => array('category_id' => $categoryId), 'order' => 'order_by ASC')); //On récupère les données
		$this->unloadModel('CategoriesRightButton'); //Déchargement du modèle
		
		//On va les rajouter dans la variable $this->request->data
		foreach($rightButtons as $k => $v) { 
			
			$this->request->data['right_button_id'][$v['right_button_id']]['top'] = $v['position']; 
			$this->request->data['right_button_id'][$v['right_button_id']]['activate'] = 1; 
		}
	}
	
/**
 * Cette fonction permet de vérifier si il faut envoyer un mail aux différents utilisateurs du site (uniquement dans le cas ou celui-ci est sécurisé)
 *
 * @param	array $datas Données de la catégorie
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 30/08/2012 by FI
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
		}*/		
	} 
	
/**
 * Cette fonction permet l'initialisation des données pour les formulaires dans le backoffice
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 * @version 0.2 - 18/12/2013 by FI - Suppression temporaire de la gestion des templates car pas forcément utile et utilisée
 * @version 0.3 - 08/04/2014 by FI - Gestion des pages volantes
 */	
	protected function _init_datas() {		
		
		$categoriesList = $this->Category->getTreeList(); //On récupère les catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
		
		/*$this->loadModel('Template');
		$templatesListTMP = $this->Template->find(array('conditions' => array('online' => 1), 'order' => 'name'));
		$templatesList = array();
		foreach($templatesListTMP as $k => $v) { $templatesList[$v['id']] = $v; }
		$this->templatesList = $templatesList;
		$this->set('templatesList', $templatesList); //On les envois à la vue*/
		
		$this->loadModel('RightButton'); //Chargement du modèle des types de posts
		$rightButton = $this->RightButton->findList(array('conditions' => array('online' => 1))); //On récupère les types de posts
		$this->unloadModel('RightButton'); //Déchargement du modèle des types de posts
		$this->set('rightButton', $rightButton); //On les envois à la vue
		
		$this->set('typesOfPage', $this->Category->typesOfPage); //On les envois à la vue
	} 

/**
 * Cette fonction permet la mise à jour du template utilisé par la catégorie
 *
 * @param 	integer $categoryId Identifiant de la catégorie
 * @param 	integer $templateId Identifiant du template utilisé
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 */	
	protected function _update_template($categoryId, $templateId) {
		
		if($templateId) {
			
			$templateDatas = $this->templatesList[$templateId];
			$templateLayout = $templateDatas['layout'];
			$templateCode = $templateDatas['code'];
			$query = "UPDATE ".$this->Category->table." SET tpl_layout = '".$templateLayout."', tpl_code = '".$templateCode."' WHERE ".$this->Category->primaryKey." = ".$categoryId;
			$this->Category->query($query);
		}
	}

/**
 * Cette fonction permet la récupération des boutons liés à la catégorie courante
 *
 * @param 	array 	$datas 		Tableau des données à passer à la vue
 * @param 	boolean $setLimit 	Indique si il faut mettre en place une limite lors de la recherche
 * @return	array	Tableau de données à passer à la vue 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 */		
	protected function _get_right_buttons_category($datas) {
					
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS;
		$cacheFile 		= "category_".$datas['category']['id']."_right_buttons";
		
		$rightButtonsCategory = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$rightButtonsCategory) {
		
			$this->loadModel('CategoriesRightButton');
			$this->CategoriesRightButton->primaryKey = 'category_id'; //Pour éviter les erreurs à l'exécution
			$rightButtonsConditions = array('category_id' => $datas['category']['id']);
			$nbRightButtons = $this->CategoriesRightButton->findCount($rightButtonsConditions);
			
			if($nbRightButtons) {
	
				$this->loadModel('RightButton');
				
				//récupération des données
				$rightButtonsList = $this->CategoriesRightButton->find(array('conditions' => $rightButtonsConditions, 'order' => 'order_by ASC'));
				foreach($rightButtonsList as $k => $rightButton) {
					
					$rightButtonsCategory[$rightButton['position']][] = $this->RightButton->findFirst(array('conditions' => array('id' => $rightButton['right_button_id'])));
				}
				
				Cache::create_cache_file($cacheFolder, $cacheFile, $rightButtonsCategory);
			} else { $rightButtonsCategory = array(); }			
		}		
		
		$datas['rightButtons'] = $rightButtonsCategory;		
		return $datas;
	}

/**
 * Cette fonction permet la récupération des enfants de la catégorie courante
 *
 * @param 	array 	$datas Données de la page
 * @return	array	Données de la page
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 20/12/2012 by FI
 * @version 0.2 - 20/03/2014 by FI - Modification formatage du tableau
 * @version 0.3 - 08/04/2014 by FI - Gestion des pages volantes
 */	
	protected function _get_children_category($datas) {
		
		//////////////////////////////////
		//   RECUPERATION DES ENFANTS   //
		if($datas['category']['display_children']) { //Si on doit récupérer les enfants			
			
			$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS;
			$cacheFile 		= "category_".$datas['category']['id']."_children";
			
			$childrenCategory = Cache::exists_cache_file($cacheFolder, $cacheFile);
			
			if(!$childrenCategory) {
			
				$children = $this->Category->getChildren($datas['category']['id'], false, false, $datas['category']['level']+1, array('conditions' => array('online' => 1, 'type!=4'))); //On récupère les enfants de la catégorie parente
				
				//Cas particulier pour les catégories "frères" le titre de la colonne de droite peut varier en fonction des besoins
				//On va donc parcourir le résultat et réorganiser le tout
				foreach($children as $k => $v) { $childrenCategory[$datas['category']['title_children']][] = $v; }
			
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
 * @version 0.1 - 20/12/2012 by FI
 * @version 0.2 - 20/03/2014 by FI - Modification formatage du tableau
 * @version 0.3 - 08/04/2014 by FI - Gestion des pages volantes
 */	
	protected function _get_brothers_category($datas) {
						
		/////////////////////////////////
		//   RECUPERATION DES FRERES   //
		if($datas['category']['display_brothers']) { //Si on doit récupérer les frères		
			
			$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS;
			$cacheFile 		= "category_".$datas['category']['id']."_brothers";
			
			$brothersCategory = Cache::exists_cache_file($cacheFolder, $cacheFile);
			
			if(!$brothersCategory) {
		
				$brothers = $this->Category->getChildren($datas['category']['parent_id'], false, false, $datas['category']['level'], array('conditions' => array('online' => 1, 'type!=4'))); //On récupère les enfants de la catégorie parente
			
				//Cas particulier pour les catégories "frères" le titre de la colonne de droite peut varier en fonction des besoins
				//On va donc parcourir le résultat et réorganiser le tout
				//foreach($brothers as $k => $v) { $brothersCategory[$v['title_brothers']][] = $v; }
				foreach($brothers as $k => $v) { $brothersCategory[$datas['category']['title_brothers']][] = $v; }
				
				Cache::create_cache_file($cacheFolder, $cacheFile, $brothersCategory);
			}
			
			$datas['brothers'] = $brothersCategory;
		}
		
		return $datas;		
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
		
		$cachingFiles = array(		
			TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."website_menu_".CURRENT_WEBSITE_ID.'.cache'
		);
		
		//Dans le cas d'une edition
		if(isset($params['identifier'])) {
			
			$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$params['identifier'].'.cache';
			$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$params['identifier'].'_brothers.cache';
			$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$params['identifier'].'_children.cache';
			$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$params['identifier'].'_right_buttons.cache';
		
			//Récuparation du path
			$path = $this->Category->getPath($params['identifier']);
			foreach($path as $k => $v) {
				
				if($v['id'] != $params['identifier']) {
				
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'.cache';
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'_brothers.cache';
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'_children.cache';
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'_right_buttons.cache';
				}			
			}
		
			//Récupération des catégories enfants
			$children = $this->Category->getChildren($params['identifier'], false);
			foreach($children as $k => $v) {
				
				if($v['id'] != $params['identifier']) {
				
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'.cache';
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'_brothers.cache';
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'_children.cache';
					$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$v['id'].'_right_buttons.cache';
				}
			}
			
		//Dans le cas d'un ajout
		} else {	
			
			if(isset($this->request->data) && !empty($this->request->data)) {
				
				$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$this->request->data['parent_id'].'.cache';
				$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$this->request->data['parent_id'].'_brothers.cache';
				$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$this->request->data['parent_id'].'_children.cache';
				$cachingFiles[] = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS."category_".$this->request->data['parent_id'].'_right_buttons.cache';
			}
		}
		
		$this->cachingFiles = $cachingFiles; 
	}
}