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
 */
	function backoffice_add() {
			
		$this->_init_datas();
		$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
		if($parentAdd) {
				
			$this->_init_category();
			$this->_edit_session();
			$this->_update_template($this->Website->id, $this->request->data['template_id']);
			$this->_update_txt_mails($this->Website->id, $this->request->data);
			$this->redirect('backoffice/websites/index');
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
 */
	function backoffice_edit($id) {
	
		$this->_init_datas();
		
		$parentEdit = parent::backoffice_edit($id, false); //On fait appel à la fonction d'édition parente		
		if($parentEdit) {
			
			$this->_edit_session();			
			$this->_update_template($id, $this->request->data['template_id']);
			$this->_update_txt_mails($id, $this->request->data);
			$this->redirect('backoffice/websites/index');
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
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
			
			//Suppression des catégories
			$this->loadModel('Category');
			$this->Category->deleteByName('website_id', $id);
			$this->unloadModel('Category');
			
			//Suppression des contacts
			$this->loadModel('Contact');
			$this->Contact->deleteByName('website_id', $id);
			$this->unloadModel('Contact');
			
			//Suppression des focus
			$this->loadModel('Focus');
			$this->Focus->deleteByName('website_id', $id);
			$this->unloadModel('Focus');
			
			//Suppression des posts
			$this->loadModel('Post');
			$this->Post->deleteByName('website_id', $id);
			$this->unloadModel('Post');
			
			//Suppression des commentaires posts
			$this->loadModel('PostsComment');
			$this->PostsComment->deleteByName('website_id', $id);
			$this->unloadModel('PostsComment');
			
			//Suppression de l'association entre les posts et les types
			$this->loadModel('PostsPostsType');
			$this->PostsPostsType->deleteByName('website_id', $id);
			$this->unloadModel('PostsPostsType');
			
			//Suppression des types de posts
			$this->loadModel('PostsType');
			$this->PostsType->deleteByName('website_id', $id);
			$this->unloadModel('PostsType');
			
			//Suppression des sliders
			$this->loadModel('Slider');
			$this->Slider->deleteByName('website_id', $id);
			$this->unloadModel('Slider');
						
			//Suppression de l'association entre le site et les groupes d'utilisateurs
			$this->loadModel('UsersGroupsWebsite');
			$this->UsersGroupsWebsite->deleteByName('website_id', $id);
			$this->unloadModel('UsersGroupsWebsite');
			
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
    
//////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PRIVEES									//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet l'initialisation des données
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 * @version 0.2 - 07/06/2012 by FI - Modification de la gestion des couleurs on travaille maintenant avec des templates
 * @todo voir si on peut pas faire autrement que $this->templatesList
 */	
	function _init_datas() {
		
		$this->loadModel('Template');
		$templatesListTMP = $this->Template->find(array('conditions' => array('online' => 1), 'order' => 'name'));
		$templatesList = array();
		foreach($templatesListTMP as $k => $v) { $templatesList[$v['id']] = $v; }
		$this->templatesList = $templatesList;
		$this->set('templatesList', $templatesList); //On les envois à la vue
	}	
		
/**
 * Cette fonction permet la création du menu racine du site Internet
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 */	
	function _init_category() {
		
		////////////////////////////////////////////////////////
		//   INITIALISATION DE LA CATEGORIE PARENTE DU SITE   //
		$this->loadModel('Category');
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
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 */
	function _edit_session() {
		$user = Session::read('Backoffice.User');
		$userRole = $user['role'];
		$userGroupId = $user['users_group_id'];
		
		$websitesListe = array(); //Liste des sites (ID => NAME)
		$websitesDetails = array(); //Détails des sites
		
		if($userRole == 'admin') {
			
			$websites = $this->Website->find(); //Récupération des données			
			
		} else if($userRole == 'website_admin') {
			
			//Récupération des sites auxquels l'utilisateurs peut se connecter
			$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
			$usersGroupsWebsites = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $userGroupId)));
			
			$usersGroupsWebsitesList = array();
			foreach($usersGroupsWebsites as $k => $v) { $usersGroupsWebsitesList[] = $v['website_id']; }
			
			$websites = $this->Website->find(array('conditions' => 'id IN ('.implode(',', $usersGroupsWebsitesList).')')); //Récupération des données	
		}
		
		foreach($websites as $k => $v) {
		
			$websitesListe[$v['id']] = $v['name'];
			$websitesDetails[$v['id']] = $v;
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
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 07/06/2012 by FI
 */	
	function _update_template($websiteId, $templateId) {
		
		$templateDatas = $this->templatesList[$templateId];
		$templateLayout = $templateDatas['layout'];
		$templateCode = $templateDatas['code'];		
		$datas = array('id' => $websiteId, 'tpl_layout' => $templateLayout, 'tpl_code' => $templateCode);
		$this->Website->save($datas);
	}

/**
 * Cette fonction permet la mise à jour des textes qui seront insérés dans les emails
 *
 * @param 	integer $websiteId 	Identifiant du site
 * @param 	array 	$datas 		Données postées
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 */	
	function _update_txt_mails($websiteId, $datas) {
			
		$txtMails = $this->components['Text']->format_for_mailing(
			array(
				'txt_mail_contact' => $datas['txt_mail_contact'],
				'txt_mail_comments' => $datas['txt_mail_comments'],
				'txt_mail_newsletter' => $datas['txt_mail_newsletter'],				
			),
			$datas['url']
		); //On fait appel au composant Text pour formater les textes des mails
		
		$datas = array(
			'id' => $websiteId, 
			'txt_mail_contact' => $txtMails['txt_mail_contact'], 
			'txt_mail_comments' => $txtMails['txt_mail_comments'], 
			'txt_mail_newsletter' => $txtMails['txt_mail_newsletter']
		);
		$this->Website->save($datas);
	}
}