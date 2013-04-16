<?php
/**
 * Permet de gérer les utilisateurs
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
class UsersController extends AppController {   

/**
 * Cette fonction permet la connexion au backoffice
 * 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 27/01/2012 by FI 
 * @version 0.2 - 20/04/2012 by FI - Gestion des messages d'erreurs 
 * @version 0.3 - 05/06/2012 by FI - Gestion de la connexion utilisateurs avec vérification des sites autorisés 
 * @version 0.4 - 11/07/2012 by FI - Mise en fonction privée de la récupération des sites Internet 
 * @version 0.5 - 02/03/2013 by FI - Modification de la gestion du role de l'utilisateur, donnée provenant maintenant de la table des groupes 
 * @version 0.6 - 16/04/2013 by FI - Mise en place de la récupération dynamique de la route pour l'interface d'administration
 */
	function login() {
		
		$this->layout = 'connect'; //Définition du layout
			
		if($this->request->data) {
			
			$data = $this->request->data; //Mise en variable des données postées			
			//$data['password'] = sha1($data['password']); //Cryptage du mot de passe
			
			//Récupération du login et du mot de passe dans des variables
			$postLogin = $data['login'];
			$postPassword = $data['password'];
			
			//Récupération de l'utilisateur
			$user = $this->User->findFirst(array('conditions' => array('login' => $postLogin)));
			
			//Si on récupère un utilisateur
			if(!empty($user)) { 
				
				//Récupération des données de l'utilisateur dans des variables
				$bddPassword = $user['password'];
				$bddOnline = $user['online'];
				
				//On va contrôler que le mot de passe saisi soit identique à celui en base de données
				if($postPassword == $bddPassword) {
				
					//Ensuite on contrôle que cet utilisateur à bien le droit de se connecter au backoffice
					if($bddOnline) { 
						
						//Récupération du groupe de cet utilisateur pour en connaître le role
						//1 --> ADMINISTRATEUR BACKOFFICE (SUPERADMIN)
						//2 --> UTILISATEUR BACKOFFICE (ADMINISTRATEUR DE SITE, REDACTEURS, ETC...)
						//3 --> UTILISATEUR FRONTOFFICE (UTILISATEUR INTRANET, CLIENT, PAGE PRIVEES)
						$this->loadModel('UsersGroup');
						$usersGroup = $this->UsersGroup->findFirst(array('conditions' => array('id' => $user['users_group_id'])));
						$bddRole = $usersGroup['role_id'];
											
						//Mise en place de la récupération dynamique de la route pour l'interface d'administration
						require_once(LIBS.DS.'config_magik.php'); 									//Import de la librairie de gestion des fichiers de configuration
						$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'routes.ini', true, false); 	//Création d'une instance
						$routesConfigs = $cfg->keys_values();										//Récupération des configurations
						
						//ADMINISTRATEUR BACKOFFICE//
						if($bddRole == 1) {
														
							$session = array(
								'User' => $user,
								'UsersGroup' => $usersGroup,
								'Websites' => $this->_init_websites_datas()
							);
														
							Session::write('Backoffice', $session); //On insère dans la variable de session les données de l'utilisateur
							$this->redirect($routesConfigs['backoffice_prefix']); //On redirige vers la page d'accueil du backoffice													
						
						//UTILISATEUR BACKOFFICE//
						} else if($bddRole == 2) {
							
							//Récupération des sites auxquels l'utilisateurs peut se connecter Via son groupe
							$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
							$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
														
							//Récupération des sites auxquels l'utilisateurs peut se connecter Via l'utilisateur
							$this->loadModel('UsersWebsite'); //Chargement du modèle
							$usersWebsites = $this->UsersWebsite->find(array('conditions' => array('user_id' => $user['id'])));
							
							$websitesList = array();
							foreach($usersGroupsWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
							foreach($usersWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
							
							//On check qu'il y ait au moins un site
							if(count($websitesList) > 0) {
								
								//$usersGroupsWebsitesList = array();
								//foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; } 	
																
								$session = array(
									'User' => $user,
									'UsersGroup' => $usersGroup,
									'Websites' => $this->_init_websites_datas(array('conditions' => 'id IN ('.implode(',', $websitesList).')'))
								);		

								//GESTION DU PLUGIN ACLS//
								if(isset($this->plugins['Acls'])) {
									
									$this->load_component('Acls', PLUGINS.DS.'acls'.DS.'class'.DS.'components');
									$session['Acl'] = $this->components['Acls']->get_auth_functions($user['users_group_id']);
								}				
								
								Session::write('Backoffice', $session); //On insère dans la variable de session les données de l'utilisateur
								$this->redirect($routesConfigs['backoffice_prefix']); //On redirige vers la page d'accueil du backoffice					
								
							} else { Session::setFlash(_("Désolé mais votre accès au backoffice n'est pas autorisé (Aucun site administrable)"), 'error'); } //Sinon on génère le message d'erreur			
							
						//UTILISATEUR FRONTOFFICE//
						} else if($bddRole == 3) {
							
							//Récupération des sites auxquels l'utilisateurs peut se connecter Via son groupe
							$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
							$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
														
							//Récupération des sites auxquels l'utilisateurs peut se connecter Via l'utilisateur
							$this->loadModel('UsersWebsite'); //Chargement du modèle
							$usersWebsites = $this->UsersWebsite->find(array('conditions' => array('user_id' => $user['id'])));
							
							$websitesList = array();
							foreach($usersGroupsWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
							foreach($usersWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
							
							//On check qu'il y ait au moins un site
							if(count($websitesList) > 0) {
							
								//On récupère la liste des sites dans un tableau
								//$usersGroupsWebsitesList = array();
								//foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; }
								
								$websiteDatas = $this->_get_website_datas(); //Récupération des données du site courant
								
								if(!in_array(CURRENT_WEBSITE_ID, $usersWebsites)) { Session::setFlash(_("Désolé mais vous ne pouvez pas accéder à ce site"), 'error'); }
								else { 
									
									$session = array(
										'User' => $user,
										'UsersGroup' => $usersGroup,
										'AuthWebsites' => $usersGroupsWebsitesList
									);
									Session::write('Frontoffice', $session); //On insère dans la variable de session les données de l'utilisateur
									$this->redirect('/'); //On redirige vers la page d'accueil du site
								}						
							} else { Session::setFlash(_("Vous ne disposez pas des droits nécessaires pour accéder à ce site"), 'error'); } //Sinon on génère le message d'erreur													
						}
					} else { Session::setFlash(_("Désolé mais votre accès au backoffice n'est pas autorisé"), 'error'); } //Sinon on génère le message d'erreur
				} else { Session::setFlash(_("Désolé mais le mot de passe ne concorde pas"), 'error'); } //Sinon on génère le message d'erreur
			} else { Session::setFlash(_("Désolé aucun utilisateur n'a été trouvé"), 'error'); } //Sinon on génère le message d'erreur				
			
			$this->request->data['password'] = ''; //On vide le mot de passe
		}
	}
	
/**
 * Cette fonction permet la déconnexion du backoffice
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 27/01/2012 by FI
 */	
	function logout() {
				
		Session::destroy();
		$this->redirect('/');		
	}
	
/**
 * Cette fonction permet de récupérer le libellé d'un utilisateur donné
 *
 * @param 	integer $id 	Identifiant de l'utilisateur
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */
	function get_user_libelle($id) {
	
		//Récupération de l'utilisateur
		$user = $this->User->findFirst(array('conditions' => array('id' => $id)));
		return array('id' => $user['id'], 'name' => $user['name'], 'second_name' => $user['second_name']);
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
	function backoffice_index() { parent::backoffice_index(false, array('id', 'name', 'second_name', 'online', 'users_group_id')); }
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/05/2012 by FI
 */
	function backoffice_add() {
	
		$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
		
		if($this->request->data) {
		
			if($this->User->id > 0) {
				
				$this->_save_assoc_datas($this->User->id);				
				if($parentAdd) { $this->redirect('backoffice/users/index'); } //On retourne sur la page de listing
			}
		}
	
		$this->_init_websites();
		$this->_init_users_groups();
	}
	/*function backoffice_add() {
	
		parent::backoffice_add(true); //On fait appel à la fonction d'ajout parente
		$this->_init_users_groups();
	}*/
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/05/2012 by FI
 */
	function backoffice_edit($id) {
	
		$parentEdit = parent::backoffice_edit($id, false); //On fait appel à la fonction d'édition parente
	
		if($this->request->data) {
			
			if($parentEdit) {						
				
				$this->_save_assoc_datas($id, true);		
				$this->redirect('backoffice/users/index'); //On retourne sur la page de listing
			}
		}
	
		$this->_init_websites();
		$this->_init_users_groups();
		$this->_get_assoc_datas($id);
	}	
	/*function backoffice_edit($id) {
	
		parent::backoffice_edit($id, true); //On fait appel à la fonction d'édition parente
		$this->_init_users_groups();
	}*/
	
/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un utilisateur on va également supprimer l'association entre les utilisateur et les sites Internet
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 10/03/2013 by FI
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
	
			//Suppression de l'association entre les posts et les types de posts
			$this->loadModel('UsersWebsite'); //Chargement du modèle
			$this->UsersWebsite->deleteByName('user_id', $id);
			$this->unloadModel('UsersWebsite'); //Déchargement du modèle
		}
		
		$this->redirect('backoffice/users/index');
	}
	
/**
 * Cette fonction permet l'affichage des logs d'un utilisateur
 *
 * @param 	integer $userId Identifiant de l'utilisateur
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/07/2012 by FI
 */
	function backoffice_logs($id) {
	
		$datas['userName'] = $this->get_user_libelle($id);
		
		$this->loadModel('UsersLog');
		$usersLogsConditions = array(
			'conditions' => array(
				'user_id' => $id,
				'website_id' => CURRENT_WEBSITE_ID,
				'type' => 1	
			),
			'order' => 'date DESC'					
		);		
		
		if(isset($this->request->data['date']) && !empty($this->request->data['date']) && $this->request->data['date'] != 'dd.mm.yy') {
			
			$searchDate = $this->components['Text']->date_human_to_array($this->request->data['date']);
			$usersLogsConditions['moreConditions'] = 'YEAR(date) = '.$searchDate['a'].' AND MONTH(date) = '.$searchDate['m'].' AND DAY(date) = '.$searchDate['j'];			
		}		
		
		$datas['usersLogs'] = $this->UsersLog->find($usersLogsConditions);		
		$this->unloadModel('UsersLog');
		
		$this->set($datas);
	}

/**
 * Cette fonction permet la récupération du rôle de l'utilisateur
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/03/2013 by FI
 */
	function backoffice_get_user_role($usersGroupId) {
	
		$this->loadModel('UsersGroup');
		$usersGroup = $this->UsersGroup->findFirst(array('conditions' => array('id' => $usersGroupId))); //On récupère la liste des sites
		$this->unloadModel('UsersGroup');
		return $usersGroup['role_id'];
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'initialisation de la liste des groupes d'utilisateurs
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */
	protected function _init_users_groups() {
	
		$this->loadModel('UsersGroup');
		$usersGroupList = $this->UsersGroup->findList(); //On récupère la liste des sites
		$this->unloadModel('UsersGroup');
		$this->set('usersGroupList', $usersGroupList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des sites Internet utilisée dans la variable de session
 *
 * @param 	array $parametres Paramètres de recherche
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 11/07/2012 by FI
 */
	protected function _init_websites_datas($parametres = null) {
	
		$this->loadModel('Website'); //Chargement du modèle
		$websites = $this->Website->find($parametres); //Récupération des données
		
		$websitesListe = array(); //Liste des sites (ID => NAME)							
		$websitesDetails = array(); //Détails des sites
		
		foreach($websites as $k => $v) { 
			
			$websitesListe[$v['id']] = $v['name'];
			$websitesDetails[$v['id']] = $v;
		}
		
		return array(
			'liste' => $websitesListe,
			'details' => $websitesDetails,
			'current' => current(array_keys($websitesListe))
		);
	}	

/**
 * Cette fonction permet l'initialisation de la liste des sites Internet paramétrés dans l'application
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 10/03/2013 by FI
 */
	protected function _init_websites() {
	
		$this->loadModel('Website');
		$websitesList = $this->Website->findList(); //On récupère la liste des sites
		$this->unloadModel('Website');
		$this->set('websitesList', $websitesList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation des données de l'association entre les utilisateur et les sites Internet
 *
 * @param	integer $userId Identifiant de l'utilisateur
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 10/03/2013 by FI
 */	
	protected function _get_assoc_datas($userId) {

		$this->loadModel('UsersWebsite'); //Chargement du modèle		
		$usersWebsite = $this->UsersWebsite->find(array('conditions' => array('user_id' => $userId))); //On récupère les données
		$this->unloadModel('UserWebsite'); //Déchargement du modèle
		//On va les rajouter dans la variable $this->request->data
		foreach($usersWebsite as $k => $v) { $this->request->data['website_id'][$v['website_id']] = 1; }
	}
	
/**
 * Cette fonction permet la sauvegarde de l'association entre les utilisateurs et les sites Internet
 *
 * @param	integer $userId 		Identifiant de l'utilisateur
 * @param	boolean $deleteAssoc 	Si vrai l'association entre les utilisateurs et les sites sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 10/03/2013 by FI
 */	
	protected function _save_assoc_datas($userId, $deleteAssoc = false) {
				
		$this->loadModel('UsersWebsite'); //Chargement du modèle

		if($deleteAssoc) { $this->UsersWebsite->deleteByName('user_id', $userId); }
		
		$websiteId = $this->request->data['website_id'];		
		foreach($websiteId as $k => $v) {
		
			if($v) { $this->UsersWebsite->save(array('user_id' => $userId, 'website_id' => $k)); }
		}
		$this->unloadModel('UsersWebsite'); //Déchargement du modèle
	}	
}