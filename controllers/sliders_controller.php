<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des sliders
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
class SlidersController extends AppController {
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/12/2015 by FI
 */	
	public function backoffice_add($redirect = false, $forceInsert = false) {

		if(parent::backoffice_add($redirect, $forceInsert)) {	 
				
			$this->_save_assoc_datas_sliders_websites($this->Slider->id);
			$this->redirect('backoffice/sliders/index'); //On retourne sur la page de listing
		}
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/12/2015 by FI
 */	
	public function backoffice_edit($id, $redirect = false) {
				
		if(parent::backoffice_edit($id, $redirect)) {
			
			$this->_save_assoc_datas_sliders_websites($this->Slider->id, true);
			$this->redirect('backoffice/sliders/index'); //On retourne sur la page de listing
		}
		
		$this->_get_assoc_datas($id);
	}
	
/**
 * Cette fonction permet l'initialisation des données de l'association
 *
 * @param	integer $sliderId Identifiant du slider
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/12/2015 by FI
 */	
	protected function _get_assoc_datas($sliderId) {

		//////////////////////////////////
		//    RECUPERATION DES SITES    //
			$this->load_model('SlidersWebsite'); //Chargement du modèle		
			$slidersWebsites = $this->SlidersWebsite->find(array('conditions' => array('slider_id' => $sliderId))); //On récupère les données
			$this->unload_model('SlidersWebsite'); //Déchargement du modèle
			
			//On va les rajouter dans la variable $this->request->data
			foreach($slidersWebsites as $k => $v) { 
								
				$this->request->data['SlidersWebsite'][$v['website_id']]['display'] = 1; 
			}
	}
	
/**
 * Cette fonction permet la sauvegarde de l'association entre les sliders et les sites Internet
 *
 * @param	integer $sliderId 		Identifiant du slider
 * @param	boolean $deleteAssoc 	Si vrai l'association entre le slider et les sites sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/12/2015 by FI
 */	
	protected function _save_assoc_datas_sliders_websites($sliderId, $deleteAssoc = false) {
		
		$this->load_model('SlidersWebsite');

		if($deleteAssoc) { $this->SlidersWebsite->deleteByName('slider_id', $sliderId); }
				
		if(isset($this->request->data['SlidersWebsite']))  {
			
			$slidersWebsites = $this->request->data['SlidersWebsite'];
						
			foreach($slidersWebsites as $websiteId => $websiteDatas) {
			
				if($websiteDatas['display']) {
										
					$this->SlidersWebsite->save(array(
						'slider_id'  => $sliderId,
						'website_id' => $websiteId
					));										
				}
			}
		}
		$this->unload_model('SlidersWebsite');
	}
    
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
			TMP.DS.'cache'.DS.'variables'.DS.'Sliders'.DS.'website_'.CURRENT_WEBSITE_ID.'.cache'
		);		
	}	
}