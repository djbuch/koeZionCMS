<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des sites Internet paramétrés
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
class WebsitesController extends AppController {
	
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 01/05/2012 by FI
 * @version 0.2 - 03/05/2012 by FI - Lors de la création d'un site il faut également créer la catégorie parente et mettre à jour la variable de session
 * @version 0.3 - 07/06/2012 by FI - Modification de la gestion des couleurs on travaille maintenant avec des templates
 * @version 0.4 - 11/04/2014 by FI - Reprise de la fonction pour alléger le nombre de requêtes
 * @version 0.5 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 * @version 0.6 - 02/04/2015 by FI - Modification de la gestion globale de la fonction, rajout de l'utilisation de la fonction parente afin de pouvoir utiliser la gestion de la traduction
 */
	function backoffice_add($redirect = false, $forceInsert = false) {
				
		$this->_init_datas(); //Initialisation des données
		
		//Si des données sont postées on va effectuer la modification de certaines données à sauvegarder
		if($this->request->data) {
		
			//Mise à jour des informations
			$this->request->data = $this->_update_template($this->request->data);
			$this->request->data = $this->_update_txt_mails($this->request->data);
		}
		
		$parentAdd = parent::backoffice_add($redirect, $forceInsert); //On fait appel à la fonction d'ajout parente
		if($parentAdd) {
			
			$this->_init_category(); //Initialisation du noeud racine du site			
			$this->_check_cache_configs(); //Modification des données en cache
			$this->_delete_cache(); //Suppression du cache global
			$this->_edit_session(); //Edition de la variable de Session
			$this->redirect('backoffice/websites/index'); //Redirection sur la page d'accueil
			
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
 * @version 0.3 - 07/06/2012 by FI - Modification de la gestion des couleurs on travaille maintenant avec des templates
 * @version 0.4 - 11/04/2014 by FI - Reprise de la fonction pour alléger le nombre de requêtes
 * @version 0.5 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 * @version 0.6 - 02/04/2015 by FI - Modification de la gestion globale de la fonction, rajout de l'utilisation de la fonction parente afin de pouvoir utiliser la gestion de la traduction
 */
	function backoffice_edit($id, $redirect = false) {
		
		$this->_init_datas(); //Initialisation des données
	
		//Si des données sont postées on va effectuer la modification de certaines données à sauvegarder	
		if($this->request->data) {
		
			//Mise à jour des informations
			$this->request->data = $this->_update_template($this->request->data);
			$this->request->data = $this->_update_txt_mails($this->request->data);
		}
		
		//On fait appel à la fonction d'édition parente
		$parentEdit = parent::backoffice_edit($id, $redirect);
		
		//Si l'édition s'est correctement déroulée
		if($parentEdit) {
			
			$this->_check_cache_configs(); //Modification des données en cache
			$this->_delete_cache(); //Suppression du cache global
			$this->_edit_session(); //Edition de la variable de Session
			$this->redirect('backoffice/websites/index'); //Redirection sur la page d'accueil
		}
	}	

/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un site Internet on doit remettre à jour la variable de session et supprimer l'ensemble des données 
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 * @version 0.2 - 08/05/2013 by FI - Amélioration de la fonction de suppression d'un site pour prendre en compte l'ensemble des tables contenant une colonne website_id
 * @version 0.3 - 03/11/2014 by FI - Reprise de la suppression des données dans les tables
 * @version 0.4 - 03/11/2014 by FI - Mise en place d'une fonction privée pour la suppression des données connectées
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
			
			$this->_edit_session();
			
			if($redirect) { $this->redirect('backoffice/websites/index'); } //On retourne sur la page de listing
			else { return true; }
		}
	}
	
/**
 * Cette fonction permet de changer la valeur du site par défaut dans la variable de session
 *
 * @param 	integer $id Identifiant du site
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 */
	function backoffice_change_default($id) {
	
		Session::write('Backoffice.Websites.current', $id);
		$this->redirect($_SERVER["HTTP_REFERER"]);
	}	
	
/**
 * Cette fonction permet de filtrer dynamiquement les templates lors de l'ajout ou de la modification d'un site
 *
 * @param 	varchar $filter Couple Layout, Version séparé par un pipe (|)
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/12/2013 by FI
 * @todo reprendre un peu la fonction pour l'alléger
 */
	function backoffice_ajax_get_templates($filter) {		
		
		$websiteDatas = Session::read('Backoffice.Websites.details');
		$currentTemplateId = $websiteDatas[CURRENT_WEBSITE_ID]['template_id'];
		$this->set('currentTemplateId', $currentTemplateId);
		$this->request->data['template_id'] = $currentTemplateId;
		
		if(!empty($filter)) {
			
			$filter = explode('|', $filter); //Récupération des conditions de recherche		
			$conditions = array("1 AND (online=1 AND layout='".$filter[0]."' AND version='".$filter[1]."') OR id=".$currentTemplateId);
		} else { $conditions = array('online' => 1); }
		
		$this->load_model('Template'); //Chargement du template
		$templatesList = $this->Template->find(array('conditions' => $conditions, 'order' => 'name')); //Récupération des données
		$this->set('templatesList', $templatesList);
	}	
    
//////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PRIVEES									//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet l'initialisation des données
 *
 * @access 	protected 
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 * @version 0.2 - 07/06/2012 by FI - Modification de la gestion des couleurs on travaille maintenant avec des templates
 * @version 0.3 - 17/12/2013 by FI - Modification de la récupération des templates suite à la mise en place de l'ajax dans le formulaire
 * @version 0.4 - 30/03/2016 by FI - Rajout de la récupération des catégories
 * @todo voir si on peut pas faire autrement que $this->templatesList
 */	
	protected function _init_datas() {
		
		$this->load_model('Template');
		$templatesListTMP = $this->Template->find(array('conditions' => array('online' => 1), 'order' => 'name'));
		$templatesList = array();
		$templatesFilter = array();		
		foreach($templatesListTMP as $k => $v) { 
			
			if(isset($templatesFilter[$v['layout']])) { //Si le layout n'est pas dans la liste
				
				if(!in_array($v['version'], $templatesFilter[$v['layout']])) { //Si la version n'est pas dans la liste
					
					$templatesFilter[$v['layout']][$v['layout'].'|'.$v['version']] = $v['version']; //On rajoute la version					
				}
			} else { 
								
				$templatesFilter[$v['layout']][$v['layout'].'|'.$v['version']] = $v['version']; //On rajoute le template (et la première version trouvée)
			}
			
			$templatesList[$v['id']] = $v; 
		}
		
		$this->templatesList = $templatesList;
		$this->set('templatesList', $templatesList); //On les envois à la vue		
		$this->set('templatesFilter', $templatesFilter); //On les envois à la vue
		
		//Template actif - Pour le sortir de la liste
		$websiteDatas = Session::read('Backoffice.Websites.details');
		$currentTemplateId = $websiteDatas[CURRENT_WEBSITE_ID]['template_id'];
		$this->set('currentTemplateId', $currentTemplateId);
		
		$this->load_model('Category');
		$categoriesList = $this->Category->getTreeList(false); //On récupère les catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}
		
/**
 * Cette fonction permet la création du menu racine du site Internet
 *
 * @access 	protected 
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 */	
	protected function _init_category() {
		
		////////////////////////////////////////////////////////
		//   INITIALISATION DE LA CATEGORIE PARENTE DU SITE   //
		$this->load_model('Category');
		$categorie = array(
			'parent_id' => 0,
			'type' => 3,
			'name' => 'Racine Site '.$this->Website->id,
			'slug' => 'racine-site-'.$this->Website->id,
			'content' => '',
			'online' => 1,
			'display_brothers' => 0,
			'title_brothers' => '',
			'page_description' => '',
			'page_keywords' => '',
			'redirect_category_id' => 0,
			'display_contact_form' => 0,
			'page_password' => '',
			'txt_secure' => '',
			'website_id' => $this->Website->id
		);
		$this->Category->save($categorie);		
	}

/**
 * Cette fonction permet la mise à jour de la variable de session contenant la liste des sites
 *
 * @access 	protected 
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 * @version 0.2 - 02/03/2013 by FI - Reprise suite à la modification de la gestion des utilisateurs
 */
	protected function _edit_session() {
		
		$backofficeSession 	= Session::read('Backoffice');
		$user 				= $backofficeSession['User'];
		$usersGroup 		= $backofficeSession['UsersGroup'];		
		$userGroupId 		= $user['users_group_id'];
		$userRole 			= $usersGroup['role_id'];
		
		$websitesListe 		= array(); //Liste des sites (ID => NAME)
		$websitesDetails 	= array(); //Détails des sites
		
		if($userRole == 1) { $websites = $this->Website->find(); } 
		else if($userRole == 2) {
			
			//Récupération des sites auxquels l'utilisateurs peut se connecter
			$this->load_model('UsersGroupsWebsite'); //Chargement du modèle
			$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $userGroupId)));
			
			$usersGroupsWebsitesList = array();
			foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; }
			
			$websites = $this->Website->find(array('conditions' => 'id IN ('.implode(',', $usersGroupsWebsitesList).')')); //Récupération des données	
		}
		
		foreach($websites as $k => $v) {
		
			$websitesListe[$v['id']] 	= $v['name'];
			$websitesDetails[$v['id']] 	= $v;
		}
		
		//Cas particulier s'il ne reste qu'un seul site il faut réinitialiser le site courant
		if(count($websitesListe) == 1) {
			
			$currentKey = array_keys($websitesListe);
			Session::write('Backoffice.Websites.current', $currentKey[0]);			
		}
		
		Session::write('Backoffice.Websites.liste', $websitesListe);
		Session::write('Backoffice.Websites.details', $websitesDetails);
	}

/**
 * Cette fonction permet la mise à jour du template utilisé par le site Internet
 *
 * @param 	integer $websiteId 	Identifiant du site
 * @param 	integer $templateId Identifiant du template utilisé
 * @access 	protected 
 * @author 	koéZionCMS
 * @version 0.1 - 07/06/2012 by FI
 * @version 0.2 - 11/04/2014 by FI - Suppression de la requête
 * @version 0.3 - 08/04/2016 by FI - Mise en place du isset
 */	
	protected function _update_template($datas) {
		
		if(isset($datas['template_id'])) { 
			
			$templateId 			= $datas['template_id']; //Récupération de l'identifiant du template		
			$templateDatas 			= $this->templatesList[$templateId];
			$datas['tpl_layout'] 	= $templateDatas['layout'];
			$datas['tpl_code'] 		= $templateDatas['code'];	
		}
		
		return $datas;
	}

/**
 * Cette fonction permet la mise à jour des textes qui seront insérés dans les emails
 *
 * @param 	integer $websiteId 	Identifiant du site
 * @param 	array 	$datas 		Données postées
 * @access 	protected 
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 * @version 0.2 - 11/04/2014 by FI - Suppression de la requête
 * @version 0.3 - 08/04/2016 by FI - Mise en place du isset
 */	
	protected function _update_txt_mails($datas) {
			
		$txtMails = $this->components['Email']->replace_links(
			array(
				'txt_mail_contact' 		=> isset($datas['txt_mail_contact']) ? $datas['txt_mail_contact'] : '',
				'txt_mail_comments' 	=> isset($datas['txt_mail_comments']) ? $datas['txt_mail_comments'] : '',
				'txt_mail_newsletter' 	=> isset($datas['txt_mail_newsletter']) ? $datas['txt_mail_newsletter'] : ''				
			),
			$datas['url']
		); //On fait appel au composant Email pour formater les textes des mails
		
		$datas['txt_mail_contact'] 		= $txtMails['txt_mail_contact']; 
		$datas['txt_mail_comments'] 	= $txtMails['txt_mail_comments'];
		$datas['txt_mail_newsletter'] 	= $txtMails['txt_mail_newsletter'];
		
		return $datas;
	}
    
/**
 * Cette fonction permet l'initialisation pour la suppression des fichier de cache
 * 
 * @param	array	$params Paramètres éventuels
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 20/12/2012 by FI
 * @version 0.2 - 18/04/2016 by FI - Gestion du cache et de la traduction
 */  
	protected function _init_caching($params = null) {		
		
		$cachingPath = TMP.DS.'cache'.DS.'variables'.DS.'Websites';
		if($this->Website->fieldsToTranslate) { $cachingPath .= DS.DEFAULT_LANGUAGE; }
		
		$this->cachingFiles = array(		
			//$cachingPath.DS.$_SERVER["HTTP_HOST"].'.cache',
			$cachingPath
			
		);
	}
}