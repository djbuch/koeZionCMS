<?php
/**
 * Modèle permettant la gestion des sliders catégories
 */
class CategoriesSlider extends Model {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 */
	var $validate = array(
		'category_id' => array(
			'rule' => 'notEmpty',
			'message' => 'CategoriesSlider.category_id'
		),
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'CategoriesSlider.name'
		)
	);
}