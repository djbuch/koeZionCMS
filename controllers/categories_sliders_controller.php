<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des sliders catégories
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
class CategoriesSlidersController extends AppController {
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 */
	function backoffice_add() {
	
		parent::backoffice_add(); //On fait appel à la fonction d'ajout parente
		$this->loadModel("Category");
		$categoriesList = $this->Category->getTreeList(false); //On récupère les catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 */	
	function backoffice_edit($id) {
			
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente	
		$this->loadModel("Category");	
		$categoriesList = $this->Category->getTreeList(false); //On récupère les catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}
}