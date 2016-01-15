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
				
			$this->_save_assoc_datas_categories_sliders_websites($this->Slider->id);
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
			
			$this->_save_assoc_datas_categories_sliders_websites($this->Slider->id, true);
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
 * @version 0.2 - 09/12/2015 by FI - Rajout de la gestion des catégories
 */	
	protected function _get_assoc_datas($sliderId) {

		//////////////////////////////////
		//    RECUPERATION DES SITES    //
		$this->load_model('CategoriesSlidersWebsite'); //Chargement du modèle		
		$categoriesSlidersWebsites = $this->CategoriesSlidersWebsite->find(array('conditions' => array('slider_id' => $sliderId))); //On récupère les données
		$this->unload_model('CategoriesSlidersWebsite'); //Déchargement du modèle
					
		//On va les rajouter dans la variable $this->request->data
		foreach($categoriesSlidersWebsites as $k => $v) { 
							
			$this->request->data['CategoriesSlidersWebsite'][$v['website_id']]['display'] = 1; 
			if($v['category_id'] == 0) { $this->request->data['CategoriesSlidersWebsite'][$v['website_id']]['display_home_page'] = 1; }
			else {
				
				$this->request->data['CategoriesSlidersWebsite'][$v['website_id']]['category_id'][$v['category_id']] = 1;
			}
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
 * @version 0.2 - 09/12/2015 by FI - Rajout de la gestion de la publication des sliders dans les catégories
 */	
	protected function _save_assoc_datas_categories_sliders_websites($sliderId, $deleteAssoc = false) {
		
		$this->load_model('CategoriesSlidersWebsite');

		if($deleteAssoc) { $this->CategoriesSlidersWebsite->deleteByName('slider_id', $sliderId); }
				
		if(isset($this->request->data['CategoriesSlidersWebsite']))  {
						
			$categoriesSlidersWebsites = $this->request->data['CategoriesSlidersWebsite'];

			$datasToSave = array(); //Données à sauvegarder
			foreach($categoriesSlidersWebsites as $websiteId => $websiteDatas) {
			
				if($websiteDatas['display']) {

					//Si on doit diffuser cet élément sur la home
					if($websiteDatas['display_home_page']) {
						
						$datasToSave[] = array(
							'category_id' => 0,
							'slider_id'  => $sliderId,
							'website_id' => $websiteId
						);
					}
					
					//Check des catégories dans lesquelles cet élément doit être publié
					foreach($websiteDatas['category_id'] as $categoryId => $isCheckedCategory) {
						
						if($isCheckedCategory) {
							
							$datasToSave[] = array(
								'category_id' => $categoryId,
								'slider_id'  => $sliderId,
								'website_id' => $websiteId
							);
						}
					}
				}
			}
			
			if($datasToSave) { $this->CategoriesSlidersWebsite->saveAll($datasToSave); }
			
		}
		$this->unload_model('CategoriesSlidersWebsite');
	}
    
/**
 * Cette fonction permet l'initialisation pour la suppression des fichier de cache
 * 
 * @param	array	$params Paramètres éventuels
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 20/12/2012 by FI
 * @version 0.2 - 09/12/2015 by FI - Rajout de la gestion de la publication dans les pages
 */  
	protected function _init_caching($params = null) {	
		
		$this->cachingFiles['website_'.CURRENT_WEBSITE_ID.'_0'] = TMP.DS.'cache'.DS.'variables'.DS.'Sliders'.DS.'website_'.CURRENT_WEBSITE_ID.'_0.cache';
		
		if(isset($this->request->data['CategoriesSlidersWebsite']) && !empty($this->request->data['CategoriesSlidersWebsite'])) {
			
			foreach($this->request->data['CategoriesSlidersWebsite'] as $websiteId => $websiteDatas) {
				
				if(isset($websiteDatas['category_id'])) {
					 
					foreach($websiteDatas['category_id'] as $categoryId => $isChecked) {
	
						$this->cachingFiles['website_'.CURRENT_WEBSITE_ID.'_'.$categoryId] = TMP.DS.'cache'.DS.'variables'.DS.'Sliders'.DS.'website_'.CURRENT_WEBSITE_ID.'_'.$categoryId.'.cache';
					}
				}
			}
		}
	}	
}