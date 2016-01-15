<?php
/**
 * Modèle permettant la gestion des catégories du site
 * Ce modèle fonctionne sur le principe de la représentation intervallaire
 * Il hérite donc du comportement Tree
 */
include_once(BEHAVIORS.DS.'tree.php'); //Inclusion du comportement
class Category extends Tree {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	var $validate = array(
		'parent_id' => array(
			'rule' => array('callback', array('check_paradox')),
			'message' => 'Category.parent_id'
		),
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'Category.name'
		),
		'redirect_category_id' => array(
			'rule' => array('callback', array('check_redirect')),
			'message' => 'Category.redirect_category_id'
		)
	);
	
/**
 * Tableau contenant les types de pages disponibles
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 08/04/2014 by FI
 */	
	var $typesOfPage = array(
		1 => "Page catégorie (Intégrée dans le menu principal)", 
		2 => "Page évènement (Non intégrée dans le menu principal)",
		4 => "Page volante (Gestion indépendante du reste des pages)"
	);
	
	
/**
 * Tableau contenant l'ensemble des champs intégrer dans l'index de recherche
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 27/01/2012 by FI
 */
	/*var $fields_to_index = array(
		'fields' => array('name', 'slug', 'content', 'page_description', 'page_keywords'),
		'display' => array(
			'title' => 'name', 
			'description' => 'page_description',
			'slug' => 'slug'
		)
	);*/

	var $searches_params = array(
		'fields' => array('name', 'slug', 'content', 'page_title', 'page_description', 'page_keywords'),
		'url' => array( 
			'url' => 'categories/view/id::id/slug::slug',
			'params' => array('slug')
		)
	);
	
/**
 * Cette fonction permet de contrôler qu'une catégorie ne soit pas son propre parent
 * 
 * @var 	integer $val Valeur du champ parent_id
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 20/04/2012 by FI
 */	
	function check_paradox($val) {
		
		$modelDatas = $this->datas; //Données postées
		
		//Il faut contrôler si on est sur un ajout ou sur une édition
		//car dans le cas de l'ajout il ne faudra pas faire le test		
		if(isset($modelDatas['id'])) { return $modelDatas['id'] != $val; }
		else { return true; }		
	}	
	
/**
 * Cette fonction permet de contrôler qu'une catégorie ne soit redirigée vers elle même
 * 
 * @var 	integer $val Valeur du champ parent_id
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 20/04/2012 by FI
 */	
	function check_redirect($val) {
		
		$modelDatas = $this->datas; //Données postées
		
		//Il faut contrôler si on est sur un ajout ou sur une édition
		//car dans le cas de l'ajout il ne faudra pas faire le test		
		if(isset($modelDatas['id'])) { return $modelDatas['id'] != $val; }
		else { return true; }		
	}
	
	
}