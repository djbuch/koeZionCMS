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
				$bddRole = $user['role'];
				$bddOnline = $user['online'];
				
				//On va contrôler que le mot de passe saisi soit identique à celui en base de données
				if($postPassword == $bddPassword) {
				
					//Ensuite on contrôle que cet utilisateur à bien le droit de se connecter au backoffice
					if($bddOnline) { 
											
						//   ADMINISTRATEUR GENERAL   //
						if($bddRole == 'admin') {
														
							$session = array(
								'User' => $user,
								'Websites' => $this->_init_websites_datas()
							);
														
							Session::write('Backoffice', $session); //On insère dans la variable de session les données de l'utilisateur						
							$this->redirect('adm'); //On redirige vers la page d'accueil du backoffice													
						
						//   ADMINISTRATEUR DE SITE   //
						} else if($bddRole == 'website_admin') {
							
							//Récupération des sites auxquels l'utilisateurs peut se connecter
							$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
							$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
														
							//On check qu'il y ait au moins un site
							if(count($usersGroupsWebsites) > 0) {
								
								$usersGroupsWebsitesList = array();
								foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; } 								
																
								$session = array(
									'User' => $user,
									'Websites' => $this->_init_websites_datas(array('conditions' => 'id IN ('.implode(',', $usersGroupsWebsitesList).')'))
								);
								
								Session::write('Backoffice', $session); //On insère dans la variable de session les données de l'utilisateur
								$this->redirect('adm'); //On redirige vers la page d'accueil du backoffice					
								
							} else { Session::setFlash(_("Désolé mais votre accès au backoffice n'est pas autorisé (Aucun site administrable)"), 'error'); } //Sinon on génère le message d'erreur			
							
						//   UTILISATEUR   //
						} else if($bddRole == 'user') {
							
							//Récupération des sites auxquels l'utilisateurs peut se connecter
							$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
							$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
							
							//On check qu'il y ait au moins un site
							if(count($usersGroupsWebsites) > 0) {
							
								//On récupère la liste des sites dans un tableau
								$usersGroupsWebsitesList = array();
								foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; }
								
								$websiteDatas = $this->_get_website_datas(); //Récupération des données du site courant
								
								if(!in_array(CURRENT_WEBSITE_ID, $usersGroupsWebsitesList)) { Session::setFlash(_("Désolé mais vous ne pouvez pas accéder à ce site"), 'error'); }
								else { 
									
									$session = array(
										'User' => $user,
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
	function backoffice_index() { parent::backoffice_index(false, array('id', 'name', 'second_name', 'role', 'online')); }
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/05/2012 by FI
 */
	function backoffice_add() {
	
		parent::backoffice_add(true); //On fait appel à la fonction d'ajout parente
		$this->_init_users_groups();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/05/2012 by FI
 */
	function backoffice_edit($id) {
	
		parent::backoffice_edit($id, true); //On fait appel à la fonction d'édition parente
		$this->_init_users_groups();
	}
	
/**
 * Cette fonction permet l'import de la liste des utilisateurs
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 13/07/2012 by FI
 */
	function backoffice_import() {
	
		if($this->request->data) { //Si des données sont postées
						
			set_time_limit(0); //Pas de limite de temps d'exécution						
			$handle = $this->components['Import']->open_file($this->request->data['file']); //On ouvre le fichier			
			if($handle !== FALSE) { //Pointer vers le fichier csv
				
				//Première étape on va vider la base de données
				$sql = "DELETE FROM ".$this->User->table." WHERE role = '".$this->request->data['role']."' AND users_group_id = ".$this->request->data['users_group_id'];
				$this->User->query($sql, false);
				
				$datasToSave = array();
				while(($data = fgetcsv($handle, 1000, ";")) !== FALSE) { //Lecture du fichier
					
					$num = count($data); //Nombre de colonnes
					
					//Tableau à sauvegarder
					$datasToSave[] = array(
						'id' => utf8_encode($data[0]),
						'name' => utf8_encode($data[1]),
						'second_name' => utf8_encode($data[2]),
						'login' => utf8_encode($data[3]),
						'password' => utf8_encode($data[4]),
						'email' => utf8_encode($data[5]),
						'role' => $this->request->data['role'],
						'users_group_id' => $this->request->data['users_group_id'],
						'online' => 1
					);										
				}
				
				$this->User->saveAll($datasToSave, true);
				
				fclose($handle);
				Session::setFlash("L'import a bien été effectué"); //Message de confirmation
			}		
		}
		
		$this->_init_users_groups();
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
			'order' => 'date ASC'					
		);		
		
		if(isset($this->request->data['date']) && !empty($this->request->data['date']) && $this->request->data['date'] != 'dd.mm.yy') {
			
			$searchDate = $this->components['Text']->date_human_to_array($this->request->data['date']);
			$usersLogsConditions['moreConditions'] = 'YEAR(date) = '.$searchDate['a'].' AND MONTH(date) = '.$searchDate['m'].' AND DAY(date) = '.$searchDate['j'];			
		}		
		
		$datas['usersLogs'] = $this->UsersLog->find($usersLogsConditions);		
		$this->unloadModel('UsersLog');
		
		$this->set($datas);
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'initialisation de la liste des groupes d'utilisateurs
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */
	function _init_users_groups() {
	
		$this->loadModel('UsersGroup');
		$usersGroupList = $this->UsersGroup->findList(); //On récupère la liste des sites
		$this->unloadModel('UsersGroup');
		$this->set('usersGroupList', $usersGroupList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des sites Internet utilisée dans la variable de session
 *
 * @param 	array $parametres Paramètres de recherche
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 11/07/2012 by FI
 */
	function _init_websites_datas($parametres = null) {
	
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
}