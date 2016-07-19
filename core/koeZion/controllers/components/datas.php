<?php
/**
 * Cette classe permet la récupération de certaines données
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
class DatasComponent extends Component {
	

//////////////////////////////////////////////////////////////////////////////////////////////////
//										DONNEES CATEGORIES										//
//////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet la récupération des boutons liés à la catégorie courante
 *
 * @param 	array 	$datas 		Tableau des données à passer à la vue
 * @param 	boolean $setLimit 	Indique si il faut mettre en place une limite lors de la recherche
 * @return	array	Tableau de données à passer à la vue 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 */		
	public function get_right_buttons_category($datas) {		
		
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS;
		$cacheFile 		= "category_".$datas['category']['id']."_right_buttons";
		
		//On contrôle si le modèle est traduit
		//Si c'est le cas on va récupérer les fichiers de cache dans un dossier spécifique à la langue
		$this->load_model('RightButton');
		$this->load_model('CategoriesRightButton');		
		if($this->RightButton->fieldsToTranslate) { $cacheFolder .= DEFAULT_LANGUAGE.DS; } 
				
		$rightButtonsCategory = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$rightButtonsCategory) {
			
			//1- Récupération des boutons qui doivent être présents sur toutes les pages
			$rightsButtonsAllPages = $this->RightButton->find(array(
				'fields' => array('content', 'display_all_pages_top', 'order_by'),
				'conditions' => array(
					'display_all_pages' => 1,
					'online' => 1
				),
				'orderBy' => 'order_by ASC'
			));
			foreach($rightsButtonsAllPages as $rightsButtonsAllPage) { $rightButtonsCategory[$rightsButtonsAllPage['display_all_pages_top']][] = array('content' => $rightsButtonsAllPage['content']); }
			
			//2- Récupération des boutons de la page en cours de consultation
			/*$rightsButtonsPages = $this->CategoriesRightButton->find(array(
				"conditions" => array('category_id' => $datas['category']['id']),
				"fields" => am(				
					array(
						"position" => "KzCategoriesRightButton.position",
						"order_by" => "KzCategoriesRightButton.order_by"
					),
					array("content" => "KzRightButton.id")
				),
				"leftJoin" => array(
					array(
						"model" => "RightButton",
						"pivot" => "KzCategoriesRightButton.right_button_id = KzRightButton.id"
					)
				),
				'orderBy' => 'order_by ASC'
			));*/
			
			$rightsButtonsPages = $this->CategoriesRightButton->find(array("conditions" => array('category_id' => $datas['category']['id'])));
			
			foreach($rightsButtonsPages as $rightsButtonsPage) { 
				
				$rightButton = $this->RightButton->findFirst(array('conditions' => array('id' => $rightsButtonsPage['right_button_id'])));
				$rightButtonsCategory[$rightsButtonsPage['position']][] = array('content' => $rightButton['content']); 
			}
			
			//Mise en cache
			Cache::create_cache_file($cacheFolder, $cacheFile, $rightButtonsCategory);	
		}		
		
		if(empty($rightButtonsCategory)) { $rightButtonsCategory = array(); }	
		
		$datas['rightButtons'] = $rightButtonsCategory;		
		return $datas;
	}

	
}