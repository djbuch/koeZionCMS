<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des modules de l'application
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
class ModulesController extends AppController {
	
	
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'affichage de la liste des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 29/05/2012 by FI
 */
	function backoffice_index() {
		
		$datas = parent::backoffice_index(true, array('id', 'name', 'order_by', 'online', 'modules_type_id'), 'order_by ASC');		
		
		$modules = array();
		foreach($datas['modules'] as $k => $v) { $modules[$v['modules_type_id']][] = $v; }
		$datas['modules'] = $modules;
		
		$modulesTypes = $this->_init_modules_types(true);
		$datas['modulesTypes'] = $modulesTypes;
		
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

		parent::backoffice_add(); //On fait appel à la fonction d'ajout parente
		$this->_init_modules_types();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	function backoffice_edit($id = null) {
				
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente
		$this->_init_modules_types();
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des types de posts
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	protected function _init_modules_types($return = false) {
		
		$this->loadModel('ModulesType'); //Chargement du modèle des types de modules
		$modulesTypes = $this->ModulesType->findList(array('conditions' => array('online' => 1))); //On récupère les types de modules		
		$this->unloadModel('ModulesType'); //Déchargement du modèle des types de modules		
		
		if($return) { return $modulesTypes; }
		else { $this->set('modulesTypes', $modulesTypes); } //On les envois à la vue
	}
}