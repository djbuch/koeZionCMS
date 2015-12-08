<?php
/**
 * Cette classe permet d'effectuer des opérations sur les utilisateurs
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
class UserComponent extends Component {

/**
 * Fonction chargée de vérifier la connexion ou l'inscription d'un utilisateur en frontoffice
 * 
 * @param array $requestDatas Données postées
 * @param array $elementDatas Données de la page
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 07/12/2015 by FI 
 */	
	public function frontoffice_login($requestDatas = false, $elementDatas = null) {

		$controller = $this->controller;
								
		//Récupération des données génériques
		$vars 			= $controller->get('vars');
		$websiteParams 	= $vars['websiteParams'];
		$websiteUrl 	= $websiteParams['url'];
		
		///////////////////////////////////////////
		//   GESTION DU FORMULAIRE DE CONNEXION  //
		if(isset($requestDatas['Login'])) {
			
			if(defined('HASH_PASSWORD') && HASH_PASSWORD) { $requestDatas['Login']['password'] = sha1($requestDatas['Login']['password']); } //Cryptage du mot de passe
		
			//Récupération du login et du mot de passe dans des variables
			$postLogin 		= $requestDatas['Login']['login'];
			$postPassword 	= $requestDatas['Login']['password'];
			
			//Récupération de l'utilisateur
			$controller->load_model('User');
			$user = $controller->User->findFirst(array(
				'fields' => am(
					$controller->User->shema,
					array('KzUsersGroup.role_id')
				),
				'conditions' => array(
					'login' => $postLogin,
					'KzUsersGroup.role_id' => 3
				),
	 			"innerJoin" => array( 
	 				array(
	 					"model" => "UsersGroup",
	 					"pivot" => "KzUser.users_group_id = KzUsersGroup.id"				
	 				)
	 			)	
			));
			
			//Si on récupère un utilisateur
			if(!empty($user)) {					
				
				//Récupération des données de l'utilisateur dans des variables
				$bddPassword 	= isset($user['password']) ? $user['password'] : $user['password'];
				$bddRole 		= isset($user['role_id']) ? $user['role_id'] : '';
				$bddOnline 		= isset($user['online']) ? $user['online'] : $user['online'];
				
				//On va contrôler que le mot de passe saisi soit identique à celui en base de données
				if($postPassword == $bddPassword) {
				
					//Ensuite on contrôle que cet utilisateur à bien le droit de se connecter au backoffice
					if($bddOnline) {
				
						if($bddRole == 3) { //3 = utilisateur frontoffice
				
							//Récupération des sites auxquels l'utilisateurs peut se connecter vie la groupe
							$controller->load_model('UsersGroupsWebsite'); //Chargement du modèle
							$usersGroupsWebsites = $controller->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $user['users_group_id'])));
							
							//Récupération des sites auxquels l'utilisateurs peut se connecter via l'utilisateur
							$controller->load_model('UsersWebsite'); //Chargement du modèle
							$usersWebsites = $controller->UsersWebsite->find(array('conditions' => array('user_id' => $user['id'])));
							
							$websitesList = array();
							foreach($usersGroupsWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
							foreach($usersWebsites as $k => $v) { $websitesList[] = $v['website_id']; }
				
							//On check qu'il y ait au moins un site
							if(count($websitesList) > 0) {
								
								if(in_array(CURRENT_WEBSITE_ID, $websitesList)) { 
													
									////////////////////////////
									//    CAS D'UN ARTICLE    //
									if(isset($elementDatas['post'])) {
										
										//Session::write('Frontoffice.Post.'.$elementDatas['post']['id'].'.isAuth', true);
										Session::write('Frontoffice.User', $user);
										$controller->redirect('posts/view/id:'.$elementDatas['post']['id'].'/slug:'.$elementDatas['post']['slug'].'/prefix:'.$elementDatas['post']['prefix'], 301);
									}
									
									//////////////////////////
									//    CAS D'UNE PAGE    //
									else if(isset($elementDatas['category'])) {
										
										//Session::write('Frontoffice.Category.'.$elementDatas['category']['id'].'.isAuth', true);
										Session::write('Frontoffice.User', $user);
										$controller->redirect("categories/view/id:".$elementDatas['category']['id']."/slug:".$elementDatas['category']['slug'], 301);
									}
									
								} else { //Gestion des erreurs					
				
									$message = _("Vous ne disposez pas des droits nécessaires pour accéder à cette page (CAS 2)");
									$controller->set('messageType', 'loginError');
									$controller->set('message', $message);
								}
							} else { //Gestion des erreurs					
				
								$message = _("Vous ne disposez pas des droits nécessaires pour accéder à cette page (CAS 1)");
								$controller->set('messageType', 'loginError');
								$controller->set('message', $message); 
							}
						} else { //Gestion des erreurs					
				
							$message = _("Désolé mais votre typologie ne vous donne pas accès à cette page");
							$controller->set('messageType', 'loginError');								
							$controller->set('message', $message);								
						}
					} else { //Gestion des erreurs					
				
						$message = _("Désolé mais votre accès n'est pas autorisé");
						$controller->set('messageType', 'loginError');
						$controller->set('message', $message); 
					}					
				} else { //Gestion des erreurs					
				
					$message = _("Désolé mais le mot de passe ne concorde pas");
					$controller->set('messageType', 'loginError');
					$controller->set('message', $message); 
				}
			} else { //Gestion des erreurs					
				
				$message = _("Désolé aucun utilisateur n'a été trouvé");
				$controller->set('messageType', 'loginError');
				$controller->set('message', $message);					
			}
		} 
		
		////////////////////////////////////////////
		//   GESTION DU FORMULAIRE D'INSCRIPTION  //
		else if(isset($requestDatas['Register'])) {
			
			
			$controller->load_model('User');
			
			//ERREUR
			if(!$controller->User->validates($requestDatas['Register'])) {
				
				$controller->set('messageType', 'registerError');
				$controller->set('errors', $controller->User->errors);
			} 
			
			//INSCRIPTION OK
			else {
				
				//Structuration des données utilisateur
				$user = am(
					$requestDatas['Register'],
					array(
						'login' => $requestDatas['Register']['email'],
						'users_group_id' => 3
					)
				);
				
				//Deux cas à traiter				
				//1- Modération : on ne valide pas le nouvel utilisateur 
				//On doit envoyer le mail de confirmation d'inscription
				if($websiteParams['moderate_new_users']) {
					
					$user['online'] = 0;
					$emailSubject 	= $websiteParams['subject_mail_inscription_user'];
					$emailContent 	= $websiteParams['txt_mail_inscription_user'];	
					$message 		= $websiteParams['txt_confirm_inscription_user'];
				} 
				
				//2- Pas de modération : on valide automatiquement le nouvel utilisateur 
				//On doit envoyer le mail de validation d'inscription
				else {
					
					$user['online'] 			= 1;
					$user['is_validated_user'] 	= 1;
					$emailSubject 				= $websiteParams['subject_mail_validated_user'];
					$emailContent 				= $websiteParams['txt_mail_validated_user'];				
					$message 					= $websiteParams['txt_confirm_validated_user'];		
				}
				
				$controller->User->save($user); //Sauvegarde de l'utilisateur
				
				//Envoi du mail
				$emailContent = $controller->components['Email']->replace_links(
					array(							
						'subject' => $emailSubject,
						'content' => $emailContent
					),
					$websiteUrl,
					array(
						'User' 	=> $user,
						'Website' 	=> $websiteParams
					)
				);
				
				$mailDatas = array(
					'subject' => $emailContent['subject'],
					'to' => $user['email'],
					'element' => ELEMENTS.DS.'email'.DS.'default',
					'vars' => array('messageContent' => $emailContent['content'])
				);
				$controller->components['Email']->send($mailDatas, $controller); //On fait appel au composant email
				
				$controller->set('messageType', 'registerSuccess');
				$controller->set('message', $message);				
			}
		}	
		
		////////////////////////////
		//   MOT DE PASSE OUBLIE  //
		else if(isset($requestDatas['Lost'])) {
			
			$postLogin 		= $requestDatas['Lost']['login'];
			
			//Récupération de l'utilisateur
			$controller->load_model('User');
			$user = $controller->User->findFirst(array(
				'fields' => am(
					$controller->User->shema,
					array('KzUsersGroup.role_id')
				),
				'conditions' => array(
					'login' => $postLogin,
					'KzUsersGroup.role_id' => 3
				),
	 			"innerJoin" => array( 
	 				array(
	 					"model" => "UsersGroup",
	 					"pivot" => "KzUser.users_group_id = KzUsersGroup.id"				
	 				)
	 			)	
			));
			
			//Si on récupère un utilisateur
			if(!empty($user)) {					
				
				//Ensuite on contrôle que cet utilisateur à bien le droit de se connecter au backoffice
				if($user['online']) {
					
					//Envoi du mail
					$emailContent = $controller->components['Email']->replace_links(
						array(							
							'subject' => $websiteParams['subject_mail_lost_password_user'],
							'content' => $websiteParams['txt_mail_lost_password_user']
						),
						$websiteUrl,
						array(
							'User' 	=> $user,
							'Website' 	=> $websiteParams
						)
					);
					
					$mailDatas = array(
						'subject' => $emailContent['subject'],
						'to' => $user['email'],
						'element' => ELEMENTS.DS.'email'.DS.'default',
						'vars' => array('messageContent' => $emailContent['content'])
					);
					$controller->components['Email']->send($mailDatas, $controller); //On fait appel au composant email
					
					$controller->set('messageType', 'registerSuccess');
					$controller->set('message', $websiteParams['txt_confirm_lost_password_user']);	
				
				} else { //Gestion des erreurs					
			
					$message = _("Désolé mais votre accès n'est pas autorisé");
					$controller->set('messageType', 'loginError');
					$controller->set('message', $message); 
				}
			} else { //Gestion des erreurs					
				
				$message = _("Désolé aucun utilisateur n'a été trouvé");
				$controller->set('messageType', 'loginError');
				$controller->set('message', $message);					
			}
		}	
	}
}