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
 */
	function backoffice_index() {
	
		$datas = parent::backoffice_index(true, array('id', 'name', 'online'), 'order_by ASC');
		$this->set($datas);
	}
}