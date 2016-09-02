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
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/12/2015 by FI
 */	
	public function backoffice_add($redirect = false, $forceInsert = false) {

		if(parent::backoffice_add($redirect, $forceInsert)) {	 
				
			$this->_save_assoc_datas_categories_focus_websites($this->Focus->id);
			$this->redirect('backoffice/focus/index'); //On retourne sur la page de listing
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
			
			$this->_save_assoc_datas_categories_focus_websites($this->Focus->id, true);
			$this->redirect('backoffice/focus/index'); //On retourne sur la page de listing
		}
		
		$this->_get_assoc_datas($id);
	}
	
/**
 * Cette fonction permet l'initialisation des données de l'association
 *
 * @param	integer $focusId Identifiant du focus
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/12/2015 by FI
 * @version 0.2 - 09/12/2015 by FI - Rajout de la gestion des catégories
 */	
	protected function _get_assoc_datas($focusId) {

		//////////////////////////////////
		//    RECUPERATION DES SITES    //
		$this->load_model('CategoriesFocusWebsite'); //Chargement du modèle		
		$categoriesFocusWebsites = $this->CategoriesFocusWebsite->find(array('conditions' => array('focus_id' => $focusId))); //On récupère les données
		$this->unload_model('CategoriesFocusWebsite'); //Déchargement du modèle
					
		//On va les rajouter dans la variable $this->request->data
		foreach($categoriesFocusWebsites as $k => $v) { 
							
			$this->request->data['CategoriesFocusWebsite'][$v['website_id']]['display'] = 1; 
			if($v['category_id'] == 0) { $this->request->data['CategoriesFocusWebsite'][$v['website_id']]['display_home_page'] = 1; }
			else {
				
				$this->request->data['CategoriesFocusWebsite'][$v['website_id']]['category_id'][$v['category_id']] = 1;
			}
		}
	}
	
/**
 * Cette fonction permet la sauvegarde de l'association entre les focus et les sites Internet
 *
 * @param	integer $focusId 		Identifiant du focus
 * @param	boolean $deleteAssoc 	Si vrai l'association entre le focus et les sites sera supprimée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/12/2015 by FI
 * @version 0.2 - 09/12/2015 by FI - Rajout de la gestion de la publication des focus dans les catégories
 */	
	protected function _save_assoc_datas_categories_focus_websites($focusId, $deleteAssoc = false) {
		
		$this->load_model('CategoriesFocusWebsite');

		if($deleteAssoc) { $this->CategoriesFocusWebsite->deleteByName('focus_id', $focusId); }
				
		if(isset($this->request->data['CategoriesFocusWebsite']))  {
						
			$categoriesFocusWebsites = $this->request->data['CategoriesFocusWebsite'];

			$datasToSave = array(); //Données à sauvegarder
			foreach($categoriesFocusWebsites as $websiteId => $websiteDatas) {
			
				if($websiteDatas['display']) {

					//Si on doit diffuser cet élément sur la home
					if($websiteDatas['display_home_page']) {
						
						$datasToSave[] = array(
							'category_id' => 0,
							'focus_id'  => $focusId,
							'website_id' => $websiteId
						);
					}
					
					//Check des catégories dans lesquelles cet élément doit être publié
					if(isset($websiteDatas['category_id']) && !empty($websiteDatas['category_id'])) {
						
						foreach($websiteDatas['category_id'] as $categoryId => $isCheckedCategory) {
							
							if($isCheckedCategory) {
								
								$datasToSave[] = array(
									'category_id' => $categoryId,
									'focus_id'  => $focusId,
									'website_id' => $websiteId
								);
							}
						}
					}
				}
			}
			
			if($datasToSave) { $this->CategoriesFocusWebsite->saveAll($datasToSave); }
			
		}
		$this->unload_model('CategoriesFocusWebsite');
	}
    
/**
 * Cette fonction permet l'initialisation pour la suppression des fichier de cache
 * 
 * @param	array	$params Paramètres éventuels
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 20/12/2012 by FI
 * @version 0.2 - 09/12/2015 by FI - Rajout de la gestion de la publication dans les pages
 * @version 0.3 - 02/09/2016 by FI - Correction du dossier de stockage des focus
 */  
	protected function _init_caching($params = null) {	
	
		$indexBase 		= 'website_'.CURRENT_WEBSITE_ID.'_';
		$cachingPath 	= TMP.DS.'cache'.DS.'variables'.DS.'Focus';
		if($this->Focus->fieldsToTranslate) { $cachingPath .= DS.DEFAULT_LANGUAGE; }
		
		$this->cachingFiles[$indexBase.'0'] = $cachingPath.DS.'website_'.CURRENT_WEBSITE_ID.'_0.cache';
		
		if(isset($this->request->data['CategoriesFocusWebsite']) && !empty($this->request->data['CategoriesFocusWebsite'])) {
			
			foreach($this->request->data['CategoriesFocusWebsite'] as $websiteId => $websiteDatas) {
				
				if(isset($websiteDatas['category_id'])) {
					
					foreach($websiteDatas['category_id'] as $categoryId => $isChecked) {
		
						$this->cachingFiles[$indexBase.$categoryId] = $cachingPath.DS.'website_'.CURRENT_WEBSITE_ID.'_'.$categoryId.'.cache';
					}
				}
			}
		}
	}	
}