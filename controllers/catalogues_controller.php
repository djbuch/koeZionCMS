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
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	

	
//////////////////////////////////////////////////////////////////////////////////////////	
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////

	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/07/2012 by FI
 */
	function backoffice_add() {
	
		parent::backoffice_add(); //On fait appel à la fonction d'ajout parente	
		$this->_init_categories();
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 23/03/2012 by FI - Lors de la modification d'une catégorie, si le champ online de celle-ci est égal à 0 on va mettre à jour l'ensemble des champs online des catégories filles
 */
	function backoffice_edit($id) {
	
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente
		$this->_init_categories();
	}	
	
/**
 * Cette fonction permet l'import de la liste des produits
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 14/07/2012 by FI
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
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'initialisation de la liste des catégories dans le site
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 16/07/2012 by FI
 */
	function _init_categories() {
	
		$this->loadModel('Category'); //Chargement du modèle des catégories
		$categoriesList = $this->Category->getTreeList(); //On récupère les catégories
		$this->unloadModel('Category'); //Déchargement du modèle des catégories
		$this->set('categoriesList', $categoriesList); //On les envois à la vue
	}	
}