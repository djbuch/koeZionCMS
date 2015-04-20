<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des types de modules de l'application
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
class ModulesTypesController extends AppController {
	
	
	
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
 */
	function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {
	
		$datas = parent::backoffice_index(true, array('id', 'name', 'online'), 'order_by ASC');
		$this->set($datas);
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/06/2014 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	function backoffice_add($redirect = true, $forceInsert = false) {

		parent::backoffice_add(); //On fait appel à la fonction d'ajout parente
		$this->_init_plugins();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/06/2014 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	function backoffice_edit($id = null, $redirect = true) {
				
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente
		$this->_init_plugins();
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des types de posts
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 23/06/2014 by FI
 */	
	protected function _init_plugins($return = false) {
		
		$this->load_model('Plugin'); //Chargement du modèle des types de modules
		$plugins = $this->Plugin->findList(array('conditions' => array('online' => 1))); //On récupère les types de modules		
		$this->unload_model('Plugin'); //Déchargement du modèle des types de modules		
		
		if($return) { return $plugins; }
		else { $this->set('plugins', $plugins); } //On les envois à la vue
	}
}