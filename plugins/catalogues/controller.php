<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des produits
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
class CataloguesController extends AppController {  
	
//////////////////////////////////////////////////////////////////////////////////////////
//										INSTALLATION									//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'installation du plugin
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */	
	function install() {
	
		//Requête SQL de création de la table des catalogues	
		$sql = "
			DROP TABLE IF EXISTS `catalogues`;
			CREATE TABLE IF NOT EXISTS `catalogues` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `reference` int(11) NOT NULL,
			  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `disponibility` int(11) NOT NULL,
			  `is_coup_coeur` int(11) NOT NULL,
			  `online` int(11) NOT NULL,
			  `created` datetime DEFAULT NULL,
			  `modified` datetime NOT NULL,
			  `created_by` int(11) NOT NULL,
			  `modified_by` int(11) NOT NULL,
			  `category_id` int(11) NOT NULL,
			  `website_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;		
		";	
		$this->loadModel('Catalogue'); //Chargement du model
		return $this->Catalogue->query($sql); //Lancement de la requête		
	}

//////////////////////////////////////////////////////////////////////////////////////////	
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */
	function backoffice_index() {
	
		$datas = parent::backoffice_index(true);
		$this->set($datas);
		
		$this->render(PLUGINS.DS.'catalogues'.DS.'views'.DS.'backoffice_index', false);
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */
	function backoffice_add() {
	
		parent::backoffice_add(); //On fait appel à la fonction d'ajout parente	
		$this->_init_categories();
		
		$this->render(PLUGINS.DS.'catalogues'.DS.'views'.DS.'backoffice_add', false);
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */
	function backoffice_edit($id) {
	
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente
		$this->_init_categories();
		
		$this->render(PLUGINS.DS.'catalogues'.DS.'views'.DS.'backoffice_edit', false);
	}	
	
/**
 * Cette fonction permet l'import de la liste des produits
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */	
	function backoffice_import() {
		
		if($this->request->data) { //Si des données sont postées
		
			set_time_limit(0); //Pas de limite de temps d'exécution			
			$handle = $this->components['Import']->open_file($this->request->data['file']); //On ouvre le fichier			
			if($handle !== FALSE) { //Pointer vers le fichier csv
		
				$categoryId = $this->request->data['category_id'];
				
				//Première étape on va vider la base de données
				$sql = "
					DELETE FROM ".$this->Catalogue->table." 
					WHERE category_id = '".$this->request->data['category_id']."' 
					AND website_id = ".CURRENT_WEBSITE_ID;
				$this->Catalogue->query($sql, false);
		
				$datasToSave = array();
				while(($datas = fgetcsv($handle, 1000, ";")) !== FALSE) { //Lecture du fichier
					
					$datasToSave[] = $this->components['Import']->format_catalogue_datas($datas, $categoryId);
				}				
				fclose($handle);
				
				$this->Catalogue->saveAll($datasToSave);
				Session::setFlash("L'import a bien été effectué"); //Message de confirmation
			}
		}
		
		$this->_init_categories();
		
		$this->render(PLUGINS.DS.'catalogues'.DS.'views'.DS.'backoffice_import', false);
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'initialisation de la liste des catégories dans le site
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */
	function _init_categories() {
	
		$this->loadModel('Category'); //Chargement du modèle des catégories
		$categoriesList = $this->Category->getTreeList(false); //On récupère les catégories
		$this->unloadModel('Category'); //Déchargement du modèle des catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}	
		
/**
 * Cette fonction permet l'initialisation des données frontoffice
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */	
	function _init_frontoffice_datas($datas = null) {
		
		if($this->params['controllerName'] == 'Categories') {
		
			$vars = $this->get('vars');
			$category = $vars['category'];
			
			$this->loadModel('Catalogue'); //Chargement du model
			$cataloguesConditions = array('online' => 1, 'category_id' => $category['id']); //Conditions de recherche par défaut
			$nbCatalogues = $this->Catalogue->findCount($cataloguesConditions);
					
			if($nbCatalogues > 0) {
			
				$datas['displayCatalogues'] = true;
				$this->pager['elementsPerPage'] = 30;
				
				/////////////////////////////////////
				//   RECUPERATION DES CATALOGUES   //
				//Définition des tris
				$defaultOrder = array();
							
				if(isset($_GET['order'])) {
				
					foreach($_GET['order'] as $column => $dir) { $defaultOrder[] = $column.' '.$dir; }
				}
				if(empty($defaultOrder)) { $defaultOrder[] = 'reference'; }
				
				//Construction des paramètres de la requête
				$cataloguesQuery = array(
					'conditions' => $cataloguesConditions,
					'limit' => $this->pager['limit'].', '.$this->pager['elementsPerPage'],
					'order' => implode(',', $defaultOrder)
				);
				$cataloguesQuery['moreConditions'] = ''; //Par défaut pas de conditions de recherche complémentaire
				
				//////////////////////////////////////////////////////////////////////////
				///  GESTION DES EVENTUELS PARAMETRES PASSES EN GET PAR L'UTILISATEUR   //
				$filterCatalogues = CataloguesController::_filter_catalogues();
				
				if(isset($filterCatalogues['moreConditions'])) {
				
					$cataloguesQuery['moreConditions'] = $filterCatalogues['moreConditions'];
					unset($filterCatalogues['moreConditions']);
				}
				
				//$datas = am($datas, $filterPosts);
				//////////////////////////////////////////////////////////////////////////
							
				$datas['catalogues'] = $this->Catalogue->find($cataloguesQuery); //Récupération des articles
				
				//On va compter le nombre d'élement de la catégorie
				//On compte deux fois le nombre de post une fois en totalité une fois en rajoutant si il est renseigné le type d'article
				//Car si on ne faisait pas cela on avait toujours la zone d'affichage des catégories qui s'affichaient lorsqu'on affichait les frères
				//même si il n'y avait pas de post
				$nbCataloguesCategory = $this->Catalogue->findCount($cataloguesConditions);
				$this->pager['totalElements'] = $this->Catalogue->findCount($cataloguesConditions, $cataloguesQuery['moreConditions']); //On va compter le nombre d'élement
				$this->pager['totalPages'] = ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); //On va compter le nombre de page
				
				$datas['is_full_page'] = 1;
				
				//Recherche du produit coup de coeur
				$coupCoeurQuery = array(
					'conditions' => array('online' => 1, 'category_id' => $category['id'], 'is_coup_coeur' => 1),
					'order' => 'reference'
				);
				$datas['coupCoeur'] = $this->Catalogue->findFirst($coupCoeurQuery);
				
				$this->set($datas);
			}	
		}	
	}
    
/**
 * Cette fonction permet de récupérer les catalogues à afficher sur le frontoffice (Dans les contrôleurs Categories)
 *
 * @param 	array	$request Critères de recherche
 * @return 	array 	Liste des catégories
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */    
    function _filter_catalogues() {
    
    	$return = array(); //Tableau retourné par la fonction
    	isset($this->request->data['SearchCatalogue']) ? $request = $this->request->data['SearchCatalogue'] : $request = array();
    	    
    	if(!empty($request)) {
    
    		unset($request['rechercher']); //On va en premier lieu supprimer la valeur du bouton rechercher
    		unset($request['order']);
    
    		$query = array();
    		foreach($request as $field => $fieldValue) {
    
    			if(!empty($fieldValue)) {
    				$query[] = " ".$field." LIKE '%".$fieldValue."%' ";
    			}
    		}
    
    		$return['moreConditions'] = implode('AND', $query);
    	}
    	return $return;
    }
}