<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des élements de portfolios
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
class PortfoliosElementsController extends AppController {

/**
 * Cette fonction permet le listing des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 11/06/2016 by FI
 */
	function backoffice_index($return = true, $fields = null, $order = null, $conditions = null) {
	
		$datas = parent::backoffice_index($return, $fields, $order, $conditions);
		$this->set($datas);
		
		$portfoliosList = $this->_get_portfolios();
		$this->set('portfoliosList', $portfoliosList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 11/06/2016 by FI
 */
	function backoffice_add($redirect = true, $forceInsert = false) {
	
		parent::backoffice_add($redirect, $forceInsert); //On fait appel à la fonction d'ajout parente
		
		$portfoliosList = $this->_get_portfolios();
		$this->set('portfoliosList', $portfoliosList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 11/06/2016 by FI
 */	
	function backoffice_edit($id, $redirect = true) {
			
		parent::backoffice_edit($id, $redirect); //On fait appel à la fonction d'édition parente
		
		$portfoliosList = $this->_get_portfolios();
		$this->set('portfoliosList', $portfoliosList); //On les envois à la vue
	}
	
/**
 * Récupération des portfolios
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 11/06/2016 by FI
 */	
	protected function _get_portfolios() {
		
		$this->load_model("Portfolio");
		$portfoliosList = $this->Portfolio->findList(array('conditions' => array('online' => 1))); //On récupère les portfolios
		return $portfoliosList;		
	}
}