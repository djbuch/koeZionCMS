<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des types de portfolios
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
class PortfoliosTypesController extends AppController {
	
//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////		
	
/**
 * Cette fonction permet de récupérer la liste des type de portfolios pour un portfolio donné
 *
 * @param 	integer $portfolioId 	Identifiant du portfolio
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */
	function get_portfolios_types($portfolioId) {
	
		$result = $this->PortfoliosType->find(
			array(
				'fields' => array('id', 'name'),
				'conditions' => array('online' => 1),
				'moreConditions' => 'KzPortfoliosType.id IN (SELECT portfolios_type_id FROM categories_portfolios_portfolios_types WHERE portfolio_id = '.$portfolioId.')'
			)
		);
		return $result;
	}	
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet la suppression d'un élément
 * Lors de la suppression d'un article on va également supprimer les associations
 *
 * @param 	integer $id Identifiant de l'élément à supprimer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */
	function backoffice_delete($id, $redirect = true) {
	
		$parentDelete = parent::backoffice_delete($id, false); //On fait appel à la fonction d'édition parente
		if($parentDelete) {
	
			//Suppression de l'association entre les posts et les types de posts
			$this->load_model('CategoriesPortfoliosPortfoliosType'); //Chargement du modèle
			$this->CategoriesPortfoliosPortfoliosType->deleteByName('portfolios_type_id', $id);
			$this->unload_model('CategoriesPortfoliosPortfoliosType'); //Déchargement du modèle
			
			if($redirect) { $this->redirect('backoffice/portfolios_types/index'); } //On retourne sur la page de listing
			else { return true; }
		}
	}
}