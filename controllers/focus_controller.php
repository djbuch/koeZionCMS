<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des focus
 * Un focus est un élément présent sur la home page du site
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
class FocusController extends AppController {
	
/**
 * Cette fonction permet l'initialisation pour la suppression des fichier de cache
 * 
 * @param	array	$params Paramètres éventuels
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 20/12/2012 by FI
 */  
	protected function _init_caching($params = null) {	
		
		$this->cachingFiles = array(		
			TMP.DS.'cache'.DS.'variables'.DS.'Focus'.DS.'website_'.CURRENT_WEBSITE_ID.'.cache'
		);		
	}
}