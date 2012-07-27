<?php
/**
 * Contrôleur permettant la gestion de la page d'attente
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
class WaitController extends Controller {

	public $auto_load_model = false;

//////////////////////////////////////////////////////////////////////////////////////////	
//										KOEZION											//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * @see Controller::beforeFilter()
 * @todo améliorer la récupération des configs...
 * @todo améliorer la récupération du menu général pour le moment une mise en cache qui me semble améliorable...
 */	
	function beforeFilter() {
		
    	$prefix = isset($this->request->prefix) ? $this->request->prefix : ''; //Récupération du préfixe
    	    	
    	//Si on est dans le backoffice
		if($prefix != 'backoffice') {
									
			$this->loadModel('Website'); //Chargement du modèle
			$datas['websiteParams'] = $this->Website->findFirst(array('conditions' => "url LIKE '%".$_SERVER['HTTP_HOST']."'"));			
			$this->set($datas);
		}
    }
    
    function index() {
    	
    	$this->layout = 'wait';
    }
}