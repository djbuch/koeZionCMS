<?php
/**
 * Permet de gérer le moteur de recherche
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
class SearchesController extends AppController {   

	public $auto_load_model = false;
	
	function index() {		
		
		//Si on a un mot recherché
		if(isset($this->request->data['q'])) {
			
			$q = $this->request->data['q'];
			$hits = $this->components['Search']->query($q);
			$this->set('hits', $hits);
			
		} else {
			
			$this->set('hits', null);
		}
	}
}