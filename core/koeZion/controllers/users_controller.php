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
 * @version 0.7 - 17/10/2014 by FI - Suppression du contrôle du mot de passe pour une utilisation locale
 * @version 0.8 - 04/06/2015 by FI - Correction récupération des données lors de l'utilisation d'un site sécurisé
 * @version 0.9 - 13/08/2015 by FI - Rajout d'un test pour vérifier si la navigation HTTPS est activée
 * @version 1.0 - 14/08/2015 by FI - Suppression du test HTTPS suite au passage de la fonctionnalité directement dans le routeur
 * @version 1.1 - 27/08/2015 by FI - Gestion template backoffice
 * @version 1.2 - 14/09/2015 by FI - Correction gestion de la variable $checkPasswordLocal
 * @version 1.3 - 05/10/2015 by FI - Rajout de defined(HASH_PASSWORD)
 * @version 1.4 - 12/10/2015 by FI - Rajout du contrôle de la valeur de HASH_PASSWORD
 * @version 1.5 - 12/05/2016 by FI - Définition des helpers à utiliser
 * @version 1.6 - 14/09/2016 by FI - Modifications suite au changement de structure de la table users
 */
	function login() {		
		
		/////////////////////////////////////////////
		//    DEFINITION DES HELPERS A UTILISER    //
			$this->helpers = array(
				array(
					'helper_name' => 'Html',
					'helper_path' => WEBROOT.DS.'templates'.DS.BACKOFFICE_TEMPLATE.DS.'views'.DS.'helpers'
				)
			);

		///////////////////////////////////////////
		//    DEFINITION DU LAJOUT A UTILISER    //
			$this->layout = WEBROOT.DS.'templates'.DS.BACKOFFICE_TEMPLATE.DS.'views'.DS.'layout'.DS.'connect';
			
		///////////////////////////////////////
		//    SI DES DONNEES SONT POSTEES    //			
			if($this->request->data) {
				
				$data = $this->request->data; //Mise en variable des données postées	
						
				if(defined('HASH_PASSWORD') && HASH_PASSWORD) { $data['password'] = sha1($data['password']); } //Cryptage du mot de passe
				
				//Récupération du login et du mot de passe dans des variables
				$postLogin 		= $data['login'];
				$postPassword 	= $data['password'];
				
				//Récupération de l'utilisateur
				$user = $this->User->findFirst(array('conditions' => array('email' => $postLogin)));
				
				//Si on récupère un utilisateur
				if(!empty($user)) { 
					
					//Récupération des données de l'utilisateur dans des variables
					$bddPassword 	= $user['password'];
					$bddOnline 		= $user['online'];
					
					//En local on peut éviter la saisie des mots de passe
					$httpHost 		= $_SERVER["HTTP_HOST"];
					$checkPassword 	= true; //Par défaut on check le password
					if(!defined('CHECK_PASSWORD_LOCAL')) { $checkPasswordLocal = 0; } else { $checkPasswordLocal = CHECK_PASSWORD_LOCAL; } //Petit contrôle au cas ou le paramètre de cette conf ne soit pas renseigné
					if(($httpHost == 'localhost' || $httpHost == '127.0.0.1') && !$checkPasswordLocal) { $checkPassword = false; }
					
					$passwordOk = true; //Par défaut la password est bon
					if($checkPassword) { $passwordOk = ($postPassword == $bddPassword); } //Sauf, éventuellement, si on souhaite le contrôle
					
					//On va contrôler que le mot de passe saisi soit identique à celui en base de données
					if($passwordOk) {
					
						//Ensuite on contrôle que cet utilisateur à bien le droit de se connecter au backoffice
						if($bddOnline) { 
							
							//Récupération du groupe de cet utilisateur pour en connaître le role
							//1 --> ADMINISTRATEUR BACKOFFICE (SUPERADMIN)
							//2 --> UTILISATEUR BACKOFFICE (ADMINISTRATEUR DE SITE, REDACTEURS, ETC...)
							//3 --> UTILISATEUR FRONTOFFICE (UTILISATEUR INTRANET, CLIENT, PAGE PRIVEES)
							$this->load_model('UsersGroup');
							$usersGroup = $this->UsersGroup->findFirst(array('conditions' => array('id' => $user['users_group_id'])));
							$bddRole = $usersGroup['role_id'];
												
							//Mise en place de la récupération dynamique de la route pour l'interface d'administration
							require_once(LIBS.DS.'config_magik.php'); 									//Import de la librairie de gestion des fichiers de configuration
							$cfg = new ConfigMagik(CONFIGS_FILES.DS.'routes.ini', true, false); 	//Création d'une instance
							$routesConfigs = $cfg->keys_values();										//Récupération des configurations
							
							//ADMINISTRATEUR BACKOFFICE//
							if($bddRole == 1) {
								
								$session = array(
									'User' => $user,
									'UsersGroup' => $usersGroup,
									'Websites' => $this->_init_websites_datas()
								);				

								//GESTION DU PLUGIN ACLS//
								$session = $this->_check_acls_plugin($user, $session);
									
								//GESTION DU PLUGIN LOCALIZATION//
								$session = $this->_check_localization_plugin($session);					
								
								//////////////////////////////////////////
								//    DEFINITION DE L'URL DE LA HOME    // 
								if(isset($usersGroup['default_home']) && !empty($usersGroup['default_home'])) { $redirectUrl = $usersGroup['default_home']; }
								else if(isset($coreConfs['backoffice_home_page']) && !empty($coreConfs['backoffice_home_page'])) { $redirectUrl = $coreConfs['backoffice_home_page']; }
								else { $redirectUrl = $routesConfigs['backoffice_prefix']; } 
								
								Session::write('Backoffice', $session); //On insère dans la variable de session les données de l'utilisateur
								$this->redirect($redirectUrl); //On redirige vers la page d'accueil du backoffice													
							
							//UTILISATEUR BACKOFFICE//
							} else if($bddRole == 2) {
								
								//Récupération des sites auxquels l'utilisateurs peut se connecter Via son groupe
								$this->load_model('UsersGroupsWebsite'); //Chargement du modèle
								$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
															
								//Récupération des sites auxquels l'utilisateurs peut se connecter Via l'utilisateur
								$this->load_model('UsersWebsite'); //Chargement du modèle
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
									$session = $this->_check_acls_plugin($user, $session);
										
									//GESTION DU PLUGIN LOCALIZATION//
									$session = $this->_check_localization_plugin($session);										
									
									//////////////////////////////////////////
									//    DEFINITION DE L'URL DE LA HOME    // 
									if(isset($usersGroup['default_home']) && !empty($usersGroup['default_home'])) { $redirectUrl = $usersGroup['default_home']; }
									else if(isset($coreConfs['backoffice_home_page']) && !empty($coreConfs['backoffice_home_page'])) { $redirectUrl = $coreConfs['backoffice_home_page']; }
									else { $redirectUrl = $routesConfigs['backoffice_prefix']; } 
									
									Session::write('Backoffice', $session); //On insère dans la variable de session les données de l'utilisateur
									$this->redirect($redirectUrl); //On redirige vers la page d'accueil du backoffice					
									
								} else { Session::setFlash(_("Désolé mais votre accès au backoffice n'est pas autorisé (Aucun site administrable)"), 'error'); } //Sinon on génère le message d'erreur			
								
							//UTILISATEUR FRONTOFFICE//
							} else if($bddRole == 3) {
								
								//Récupération des sites auxquels l'utilisateurs peut se connecter via son groupe
								$this->load_model('UsersGroupsWebsite'); //Chargement du modèle
								$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
															
								//Récupération des sites auxquels l'utilisateurs peut se connecter via l'utilisateur
								$this->load_model('UsersWebsite'); //Chargement du modèle
								$usersWebsites = $this->UsersWebsite->find(array('conditions' => array('user_id' => $user['id'])));
								
								$websitesList = array();
								foreach($usersGroupsWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
								foreach($usersWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
								
								//On check qu'il y ait au moins un site
								if(count($websitesList) > 0) {
								
									//On récupère la liste des sites dans un tableau
									//$usersGroupsWebsitesList = array();
									//foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; }
									
									$websiteDatas = $this->components['Website']->get_website_datas(); //Récupération des données du site courant
									
									if(!in_array(CURRENT_WEBSITE_ID, $websitesList)) { Session::setFlash(_("Désolé mais vous ne pouvez pas accéder à ce site"), 'error'); }
									else { 
										
										$session = array(
											'User' => $user,
											'Customer' => $user,
											'UsersGroup' => $usersGroup,
											'AuthWebsites' => $websitesList
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
		$this->redirect('/connexion');		
	}
	
/**
 * Cette fonction permet la déconnexion du frontoffice
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 08/12/2015 by FI
 */	
	function frontoffice_logout() {
				
		Session::delete('Frontoffice');
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
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) { 
		
		parent::backoffice_index(false, array('id', 'lastname', 'firstname', 'online', 'users_group_id')); 
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/05/2012 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 * @version 0.3 - 08/12/2015 by FI - Rajout de la fonction _send_user_mail()			
 */
	function backoffice_add($redirect = true, $forceInsert = false) {
	
		$parentAdd = parent::backoffice_add($forceInsert); //On fait appel à la fonction d'ajout parente
		
		if($parentAdd) {
				
			$this->_save_assoc_datas($this->User->id);	
			$this->_send_user_mail();						
			$this->redirect('backoffice/users/index');
		}
	
		$this->_init_websites();
		$this->_init_users_groups();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/05/2012 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 * @version 0.3 - 08/12/2015 by FI - Rajout de la fonction _send_user_mail()
 */
	function backoffice_edit($id, $redirect = false) {
	
		$parentEdit = parent::backoffice_edit($id, $redirect); //On fait appel à la fonction d'édition parente
	
		if($parentEdit) {
				
			$this->_save_assoc_datas($id, true);
			$this->_send_user_mail();			
			$this->redirect('backoffice/users/index'); 
		}
	
		$this->_init_websites();
		$this->_init_users_groups();
		$this->_get_assoc_datas($id);
	}
	
/**
 * Cette fonction permet l'import de nouveaux utilisateurs
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/09/2016 by FI
 */
	function backoffice_import() {
		
		if($this->request->data) {
			
			//Pas de limite de temps d'exécution et augmentation de la mémoire
			set_time_limit(0);	
			ini_set("memory_limit","256M");
			
			//Chargement du composant Import
			$this->load_component('Import');
			$ImportComponent = $this->components['Import'];
						
			$handle = $ImportComponent->open_file($this->request->data['users_file']); //On ouvre le fichier
					
			if($handle !== FALSE) { //Pointeur vers le fichier csv
								
				$delimiter 	= ";";
				$line 		= 1;
				$errors = array();
				while(($datas = fgetcsv($handle, 3000, $delimiter)) !== FALSE) { //Lecture du fichier
				
					//Récupération des données formatés et rajout de nouveaux champs
					$user 						= $ImportComponent->format_datas($datas);					
					$user['online'] 			= 1;					
					$user['users_group_id'] 	= $this->request->data['users_group_id'];					
					if(empty($user['password'])) { $user['password'] = $this->components['Text']->random_code(); }
					
					//Si elles sont valides
					if($this->User->validates($user)) { 
						
						$this->User->save($user);
						$this->_save_assoc_datas($this->User->id, true);						
					} 
					else { $errors[] = _("Erreur ligne")." ".$line." : ".implode(', ', $this->User->errors); }
					$line++;
				}
				fclose($handle);

				//Si on a eu des erreurs
				if($errors) { 
					
					$this->request->data = false;
					$message = _("L'import est terminé, mais des erreurs se sont produites"." :<br />".implode('<br />', $errors));
					Session::setFlash($message, 'error'); 
				} else { 
					
					$this->request->data = false;
					Session::setFlash(_("L'import est terminé")); 
				}
			}
		}
		
		$this->_init_websites();
		$this->_init_users_groups();
	}
	
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
			$this->load_model('UsersWebsite'); //Chargement du modèle
			$this->UsersWebsite->deleteByName('user_id', $id);
			$this->unload_model('UsersWebsite'); //Déchargement du modèle
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
		
		$this->load_model('UsersLog');
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
		$this->unload_model('UsersLog');
		
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
	
		$this->load_model('UsersGroup');
		$usersGroup = $this->UsersGroup->findFirst(array('conditions' => array('id' => $usersGroupId))); //On récupère la liste des sites
		$this->unload_model('UsersGroup');
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
	
		$this->load_model('UsersGroup');
		$usersGroupList = $this->UsersGroup->findList(); //On récupère la liste des sites
		$this->unload_model('UsersGroup');
		$this->set('usersGroupList', $usersGroupList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des sites Internet utilisée dans la variable de session
 *
 * @param 	array $parametres Paramètres de recherche
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 11/07/2012 by FI
 * @version 0.2 - 08/06/2016 by SS - Ajout de la détection du site courant lorqu'on se connecte au backoffice
 */
	protected function _init_websites_datas($parametres = null) {

		$this->load_model('Website'); //Chargement du modèle
		$websites = $this->Website->find($parametres); //Récupération des données

		$websitesListe = array(); //Liste des sites (ID => NAME)							
		$websitesDetails = array(); //Détails des sites
		$websitesCurrent = array(); //Site courant

		foreach($websites as $k => $v) {

			$websitesListe[$v['id']] = $v['name'];
			$websitesDetails[$v['id']] = $v;
			if(preg_match('/'.$_SERVER['SERVER_NAME'].'/', $v['url'])) { $websitesCurrent[$v['id']] = $v['name']; }
		}

		return array(
			'liste' => $websitesListe,
			'details' => $websitesDetails,
			'current' => (!empty($websitesCurrent) ? current(array_keys($websitesCurrent)) : current(array_keys($websitesListe)))
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
	
		$this->load_model('Website');
		$websitesList = $this->Website->findList(); //On récupère la liste des sites
		$this->unload_model('Website');
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

		$this->load_model('UsersWebsite'); //Chargement du modèle		
		$usersWebsite = $this->UsersWebsite->find(array('conditions' => array('user_id' => $userId))); //On récupère les données
		$this->unload_model('UserWebsite'); //Déchargement du modèle
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
				
		$this->load_model('UsersWebsite'); //Chargement du modèle

		if($deleteAssoc) { $this->UsersWebsite->deleteByName('user_id', $userId); }
		
		$websiteId = $this->request->data['website_id'];		
		foreach($websiteId as $k => $v) {
		
			if($v) { $this->UsersWebsite->save(array('user_id' => $userId, 'website_id' => $k)); }
		}
		$this->unload_model('UsersWebsite'); //Déchargement du modèle
	}	
	
/**
 * Contrôle de l'existence du plugin ACL
 *
 * @param	array $user 	Données utilisateur
 * @param	array $session Tableau contenant les données de la variable de Session
 * @return	array Données de la variable de Session
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 18/03/2015 by FI
 * @version 0.2 - 24/04/2015 by FI - Rajout de $user
 */	
	protected function _check_acls_plugin($user, $session) {
		
		if(isset($this->plugins['Acls'])) {
			
			$this->load_component('Acls', PLUGINS.DS.'acls'.DS.'controllers'.DS.'components');
			$session['Acl'] = $this->components['Acls']->get_auth_functions($user['users_group_id']);
		}
		
		return $session;
	}
	
/**
 * Contrôle de l'existence du plugin LOCALIZATION
 *
 * @param	array $session Tableau contenant les données de la variable de Session
 * @return	array Données de la variable de Session
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 18/03/2015 by FI
 */	
	protected function _check_localization_plugin($session) {
		
		if(isset($this->plugins['Localization'])) {
			
			$this->load_model('Language');
			$languagesTMP = $this->Language->find(array(
				'conditions' => array('online' => 1),
				'orderBy' => 'order_by ASC'
			));
			$languages 	= array();
			foreach($languagesTMP as $language) { $languages[$language['code']] = $language; }
			$session['Languages'] = $languages;
		}
		
		return $session;
	}
	
/**
 * Fonction chargée d'envoyer si besoin les mails de confirmation ou de refus d'inscription
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 08/12/2015 by FI
 */	
	protected function _send_user_mail() {
			
		//Si on a coché l'envoi de mail de validation
		if($this->request->data['is_validated_user']) {
			
			$emailSubject 	= $this->request->data['subject_mail_validated_user'];
			$emailContent 	= $this->request->data['txt_mail_validated_user'];	
			
			//Cas particulier si l'utilisateur est validé mais que online = 0 on va le corriger
			if(!$this->request->data['online']) {
				
				$userOnline = array('id' => $this->User->id, 'online' => 1);				
				$this->User->save($userOnline);
			}
		}
		
		//Si on a coché l'envoi de mail de refus
		else if($this->request->data['is_refused_user']) {
			
			$emailSubject 	= $this->request->data['subject_mail_refused_user'];
			$emailContent 	= $this->request->data['txt_mail_refused_user'];
			
			//Cas particulier si l'utilisateur est refusé mais que online = 1 on va le corriger
			if($this->request->data['online']) {
				
				$userOnline = array('id' => $this->User->id, 'online' => 0);				
				$this->User->save($userOnline);
			}
		}
		
		//Si l'un ou l'autre est coché
		if(isset($emailSubject)) {
			
			$websitesSession 		= Session::read('Backoffice.Websites'); //Récupération de la variable de session
			$currentWebsiteId 		= $websitesSession['current']; //Récupération du site courant
			$currentWebsiteDatas 	= $websitesSession['details'][$currentWebsiteId]; //Récupération du site courant
			
			$user 					= $this->request->data;
			
			//Envoi du mail
			$emailContent = $this->components['Email']->replace_links(
				array(							
					'subject' => $emailSubject,
					'content' => $emailContent
				),
				$currentWebsiteDatas['url'],
				array(
					'User' 	=> $user,
					'Website' 	=> $currentWebsiteDatas
				)
			);
			
			$mailDatas = array(
				'subject' => $emailContent['subject'],
				'to' => $user['email'],
				'element' => ELEMENTS.DS.'email'.DS.'default',
				'vars' => array('messageContent' => $emailContent['content'])
			);
			$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email			
		}		
	}
}