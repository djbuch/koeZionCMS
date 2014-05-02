<?php
/**
 * Cette classe permet la récupération du site courant en Front
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
class WebsiteComponent extends Component {
	
/**
 * Cette fonction permet la récupération des données du site courant
 *
 * @return 	array Données du site Internet
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 * @version 0.2 - 14/06/2012 by FI - Modification de la récupération du site pour la boucle locale - On récupère le premier site de la liste et plus celui avec l'id 1 pour éviter les éventuelles erreurs
 * @version 0.3 - 04/09/2012 by FI - Mise en place d'un passage de paramètre en GET pour pouvoir changer de site en local
 * @version 0.4 - 02/04/2014 by FI - Mise en place d'un passage de paramètre en GET pour pouvoir changer le host du site en local
 */
	public function get_website_datas() {
				
		if(isset($_GET['hack_ws_host'])) { Session::write('Frontoffice.hack_ws_host', $_GET['hack_ws_host']); }
		$hackWsHost = Session::read('Frontoffice.hack_ws_host');
		
		$httpHost = !empty($hackWsHost) ? $hackWsHost : $_SERVER["HTTP_HOST"]; //Récupération de l'url		
		
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Websites'.DS;
		$cacheFile 		= $httpHost;	
		
		$website = Cache::exists_cache_file($cacheFolder, $cacheFile);
	
		if(!$website) {
			
			$this->loadModel('Website'); //Chargement du modèle
	
			//HACK SPECIAL LOCAL POUR CHANGER DE SITE pour permettre la passage de l'identifiant du site en paramètre
			if(isset($_GET['hack_ws_id'])) { Session::write('Frontoffice.hack_ws_id', $_GET['hack_ws_id']); }
			$hackWsId = Session::read('Frontoffice.hack_ws_id');
	
			if($httpHost == 'localhost' || $httpHost == '127.0.0.1') {
	
				if($hackWsId) { $websiteId = $hackWsId; }
				else {
	
					$websites = $this->Website->findList(array('order' => 'id ASC'));
					$websiteId = current(array_keys($websites));
				}
	
				$websiteConditions = array('conditions' => array('id' => $websiteId, 'online' => 1));
	
			} else {
	
				if($hackWsId) { $websiteConditions = array('conditions' => array('id' => $hackWsId, 'online' => 1)); }
				else { $websiteConditions = array('conditions' => "url LIKE '%".$httpHost."%' AND online = 1"); }
			}
			
			$website = $this->Website->findFirst($websiteConditions);
	
			Cache::create_cache_file($cacheFolder, $cacheFile, $website);
		}
	
		if(!defined('CURRENT_WEBSITE_ID')) { define('CURRENT_WEBSITE_ID', $website['id']); }
		
		return array(
			'layout' => 	$website['tpl_layout'],
			'website' =>	$website 	
		);
	}
}