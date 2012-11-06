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
class SearchsController extends AppController {   
	
	function rechercher() {
	
		$this->auto_render = false;

		//Si on a un mot recherché
		if(isset($this->request->data['q'])) { $q = strip_tags($this->request->data['q']); } 
		else { $q = ''; }
		
		$this->redirect('recherche', null, 'q='.$q); //Redirection
	}
		
	function index() {		
		
		//Si on a un mot recherché
		if(isset($this->request->data['q'])) {
			
			$q = $this->request->data['q'];			
			$hits = $this->Search->find(array('conditions' => "datas LIKE '%".$q."%' AND website_id = ".CURRENT_WEBSITE_ID));			
			$this->set('hits', $hits);
			$this->set('q', $q);
			
		} else {
			
			$this->set('hits', null);
			$this->set('q', null);
		}
	}
}