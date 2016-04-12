<?php
/**
 * Modèle permettant la gestion des types de portfolios
 */
class PortfoliosType extends Model {   

///////////////////	
//   VARIABLES   //	
///////////////////
		
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */	
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'PortfoliosType.name'
		)
	);
	
///////////////////	
//   FONCTIONS   //	
///////////////////
	
/**
 * Cette fonction récupère les types de portfolios
 *
 * @varchar	integer $categoryId Identifiant de la catégorie
 * @return	array	Tableau contenant les types de portfolios
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/04/2016 by FI
 */	
	function get_for_front($categoryId = null) {
		
		///////////////////////////////////////////
		//   RECUPERATION DES TYPES D'ARTICLES   //
		//Dans le cas ou est indiqué une catégorie
		if(isset($categoryId) && !empty($categoryId)) {
			
			$params = array(
				'tables' => array(
					'portfolios_types' => 'KzPortfoliosType',	
					'categories_portfolios_portfolios_types' => 'KzCategoriesPortfoliosPortfoliosType',	
					'portfolios' => 'KzPortfolio',	
				),
				'conditions' => array(
					"KzCategoriesPortfoliosPortfoliosType.category_id = ".$categoryId,
					"KzCategoriesPortfoliosPortfoliosType.portfolios_type_id = KzPortfoliosType.id",
					"KzPortfoliosType.online = 1",
					"KzCategoriesPortfoliosPortfoliosType.portfolio_id = KzPortfolio.id",
					"KzPortfolio.online = 1"
				)				
			);
			$portfoliosTypes = $this->find($params);

			
		//Dans le cas ou aucune catégorie n'est indiquée
		} else {
						
			$params = array('conditions' => array('online' => 1));
			$portfoliosTypes = $this->find($params);
		}
		
		return $portfoliosTypes;
	}
}