<?php
/**
 * Permet de gérer les groupes d'utilisateurs
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
class UsersGroupsController extends AppController {   
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */
	function backoffice_add() {
	
		$parentAdd = parent::backoffice_add(false); //On fait appel à la fonction d'ajout parente
		
		if($this->request->data) {
		
			if($this->UsersGroup->id > 0) {
				
				$this->_save_assoc_datas($this->UsersGroup->id);				
				if($parentAdd) { $this->redirect('backoffice/users_groups/index'); } //On retourne sur la page de listing
			}
		}
	
		$this->_init_websites();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */
	function backoffice_edit($id) {
	
		$parentEdit = parent::backoffice_edit($id, false); //On fait appel à la fonction d'édition parente
	
		if($this->request->data) {
			
			if($parentEdit) {						
				
				$this->_save_assoc_datas($id, true);		
				$this->redirect('backoffice/users_groups/index'); //On retourne sur la page de listing
			}
		}
	
		$this->_init_websites();
		$this->_get_assoc_datas($id);
	}	
	
/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un article on va également supprimer l'association entre les groupes d'utilisateur et les sites Internet
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
	
			//Suppression de l'association entre les posts et les types de posts
			$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle
			$this->UsersGroupsWebsite->deleteByName('users_group_id', $id);
			$this->unloadModel('UsersGroupsWebsite'); //Déchargement du modèle
		}
		
		$this->redirect('backoffice/users_groups/index');
	}	
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'initialisation de la liste des sites Internet paramétrés dans l'application
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 18/05/2012 by FI
 */
	protected function _init_websites() {
	
		$this->loadModel('Website');
		$websitesList = $this->Website->findList(); //On récupère la liste des sites
		$this->unloadModel('Website');
		$this->set('websitesList', $websitesList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation des données de l'association entre les groupes d'utilisateur et les sites Internet
 *
 * @param	integer $usersGroupId Identifiant du groupe d'utilisateur
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */	
	protected function _get_assoc_datas($usersGroupId) {

		$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle		
		$usersGroupsWebsite = $this->UsersGroupsWebsite->find(array('conditions' => array('users_group_id' => $usersGroupId))); //On récupère les données
		$this->unloadModel('UsersGroupsWebsite'); //Déchargement du modèle
		//On va les rajouter dans la variable $this->request->data
		foreach($usersGroupsWebsite as $k => $v) { $this->request->data['website_id'][$v['website_id']] = 1; }
	}
	
/**
 * Cette fonction permet la sauvegarde de l'association entre les groupes d'utilisateurs et les sites Internet
 *
 * @param	integer $usersGroupId 	Identifiant du groupe d'utilisateur
 * @param	boolean $deleteAssoc 	Si vrai l'association entre les groupes d'utilisateurs et les sites sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */	
	protected function _save_assoc_datas($usersGroupId, $deleteAssoc = false) {
				
		$this->loadModel('UsersGroupsWebsite'); //Chargement du modèle

		if($deleteAssoc) { $this->UsersGroupsWebsite->deleteByName('users_group_id', $usersGroupId); }
		
		$websiteId = $this->request->data['website_id'];		
		foreach($websiteId as $k => $v) {
		
			if($v) { $this->UsersGroupsWebsite->save(array('users_group_id' => $usersGroupId, 'website_id' => $k)); }
		}
		$this->unloadModel('UsersGroupsWebsite'); //Déchargement du modèle
	}	
}