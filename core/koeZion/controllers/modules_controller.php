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
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 * @version 0.3 - 14/10/2015 by FI - Réorganisation du passage de paramètres à la vue
 * @version 0.4 - 08/04/2016 by FI - Réorganisation des données dans le tableau
 */
	function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {
		
		$datas 			= parent::backoffice_index(true, array('id', 'name', 'order_by', 'online', 'modules_type_id'), 'order_by ASC');
		$modulesTypes 	= $this->_init_modules_types(true);		
		$modules 		= array();
		$modulesTMP 	= array();
		
		foreach($datas['modules'] as $k => $v) { 
			
			if(isset($modulesTypes[$v['modules_type_id']])) { $modulesTMP[$v['modules_type_id']][] = $v; }
		}
				
		foreach($modulesTypes as $k => $v) {
			
			if(isset($modulesTMP[$k])) { $modules[$v] = $modulesTMP[$k]; }
		}
		
		$datas['modules'] = $modules;		
		$this->set($datas);
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 14/08/2014 by FI - Mise en place de la gestion du champ plugin_id
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	function backoffice_add($redirect = true, $forceInsert = false) {
		
		if($this->request->data && $this->request->data['modules_type_id']) {
			
			//Récupération de l'identifiant du plugin
			$this->load_model('ModulesType');
			$module = $this->ModulesType->findFirst(array('conditions' => array('id' => $this->request->data['modules_type_id'])));
			if($module) { $this->request->data['plugin_id'] = $module['plugin_id']; }			
		}
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
 * @version 0.2 - 14/08/2014 by FI - Mise en place de la gestion du champ plugin_id
 * @version 0.3 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	function backoffice_edit($id = null, $redirect = true) {
		
		if($this->request->data && $this->request->data['modules_type_id']) {
			
			//Récupération de l'identifiant du plugin
			$this->load_model('ModulesType');
			$module = $this->ModulesType->findFirst(array('conditions' => array('id' => $this->request->data['modules_type_id'])));
			if($module) { $this->request->data['plugin_id'] = $module['plugin_id']; }			
		}
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente
		$this->_init_modules_types();
	}
		
//////////////////////////////////////////////////////////////////////////////////////////
//									PRIVEES / PROTEGEES									//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet l'initialisation de la liste des types de posts
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */	
	protected function _init_modules_types($return = false) {
		
		$this->load_model('ModulesType'); //Chargement du modèle des types de modules
		$modulesTypes = $this->ModulesType->findList(array(
			'conditions' => array('online' => 1),
			'orderBy' => 'order_by ASC'
		)); //On récupère les types de modules		
		$this->unload_model('ModulesType'); //Déchargement du modèle des types de modules
		if($return) { return $modulesTypes; }
		else { $this->set('modulesTypes', $modulesTypes); } //On les envois à la vue
	}
}