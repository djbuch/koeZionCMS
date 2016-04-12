<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des portfolios
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
class PortfoliosController extends AppController {

/**
 * Cette fonction permet le listing des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */
	function backoffice_index($return = true, $fields = null, $order = null, $conditions = null) {
	
		$datas = parent::backoffice_index($return, $fields, $order, $conditions);
		$this->set($datas);
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */
	function backoffice_add($redirect = false, $forceInsert = false) {

		$parentAdd = parent::backoffice_add($redirect, $forceInsert); //On fait appel à la fonction d'ajout parente
		
		if($this->request->data) {
					
			if($this->Portfolio->id > 0 && $parentAdd) {		 
				
				$this->_save_assoc_datas_portfolios_types($this->Portfolio->id);	
				$this->redirect('backoffice/portfolios/index'); //On retourne sur la page de listing
			}
		}
				
		$this->_set_categories();
		$this->_set_portfolios_types();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */	
	function backoffice_edit($id, $redirect = false) {

		$parentEdit = parent::backoffice_edit($id, $redirect); //On fait appel à la fonction d'ajout parente
		
		if($this->request->data) {
					
			if($parentEdit) {			 
				
				$this->_save_assoc_datas_portfolios_types($this->Portfolio->id, true);	
				$this->redirect('backoffice/portfolios/index'); //On retourne sur la page de listing
			}
		}
				
		$this->_set_categories();
		$this->_set_portfolios_types();
		$this->_get_assoc_datas($id);
	}

/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un portfolio on va également supprimer l'association entre le portfolio et les types de portfolio
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */
	public function backoffice_delete($id, $redirect = false) {
	
		$parentDelete = parent::backoffice_delete($id, $redirect); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
			
			//Suppression de l'association entre les posts et les types de posts
			$this->load_model('CategoriesPortfoliosPortfoliosType'); //Chargement du modèle
			$this->CategoriesPortfoliosPortfoliosType->deleteByName('portfolio_id', $id);
			$this->unload_model('CategoriesPortfoliosPortfoliosType'); //Déchargement du modèle
			
			if($redirect) { $this->redirect('backoffice/portfolios/index'); } //On retourne sur la page de listing
			else { return true; }
		}
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des catégories dans le site
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */	
	protected function _set_categories() {

		$this->load_model('Category'); //Chargement du modèle des catégories
		$categoriesList = $this->Category->getTreeList(false); //On récupère les catégories
		$this->unload_model('Category'); //Déchargement du modèle des catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation de la liste des types de portfolios
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 * @version 0.2 - 04/12/2013 by FI - Classement des types par rubriques
 */	
	protected function _set_portfolios_types() {
		
		$this->load_model('PortfoliosType'); //Chargement du modèle des types de portfolios
		$portfoliosTypes = $this->PortfoliosType->findList(array('conditions' => array('online' => 1))); //On récupère les types de portfolios		
		$this->unload_model('PortfoliosType'); //Déchargement du modèle des types de portfolios
		$this->set('portfoliosTypes', $portfoliosTypes); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'initialisation des données de l'association entre le portfolio et les types de portfolio
 *
 * @param	integer $portfolioId Identifiant du portfolio
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */	
	protected function _get_assoc_datas($portfolioId) {

		////////////////////////////////////////////
		//    RECUPERATION DES TYPES D'ARTICLE    //
			$this->load_model('CategoriesPortfoliosPortfoliosType'); //Chargement du modèle		
			$portfoliosPortfoliosTypes = $this->CategoriesPortfoliosPortfoliosType->find(array('conditions' => array('portfolio_id' => $portfolioId))); //On récupère les données
			$this->unload_model('CategoriesPortfoliosPortfoliosType'); //Déchargement du modèle
			
			//On va les rajouter dans la variable $this->request->data
			foreach($portfoliosPortfoliosTypes as $k => $v) { $this->request->data['portfolios_type_id'][$v['portfolios_type_id']] = 1; }
	}
	
/**
 * Cette fonction permet la sauvegarde de l'association entre les articles, les catégories et les sites Internet
 * Elle gère également la sauvegarde entre les articles, les types d'articles et les catégories
 *
 * @param	integer $portfolioId 	Identifiant du portfolio
 * @param	boolean $deleteAssoc 	Si vrai l'association avec le portfolio sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */	
	protected function _save_assoc_datas_portfolios_types($portfolioId, $deleteAssoc = false) {
		
		$this->load_model('CategoriesPortfoliosPortfoliosType');

		if($deleteAssoc) { $this->CategoriesPortfoliosPortfoliosType->deleteByName('portfolio_id', $portfolioId); }
						
		///////////////////////////////////////////////////
		//    GESTION DE L'AJOUT DES TYPES D'ARTICLES    //
		if(isset($this->request->data['portfolios_type_id']))  {

			$portfoliosTypes = $this->request->data['portfolios_type_id'];
			foreach($portfoliosTypes as $portfoliosTypeId => $isPortfoliosTypeChecked) {
			
				if($isPortfoliosTypeChecked) {
			
					$this->CategoriesPortfoliosPortfoliosType->save(array(
						'category_id' 			=> $this->request->data['category_id'],
						'portfolio_id' 			=> $portfolioId,
						'portfolios_type_id'	=> $portfoliosTypeId
					));
				}
			}
		}
		
		$this->unload_model('CategoriesPortfoliosPortfoliosType');
	}
}