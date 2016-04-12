<?php
/**
 * Modèle permettant la gestion des portfolios
 */
class Portfolio extends Model {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 11/06/2016 by FI
 */
	var $validate = array(
		'category_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Portfolio.category_id'
		),
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'Portfolio.name'
		)
	);
}