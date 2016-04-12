<?php
/**
 * Modèle permettant la gestion des éléments de portfolios
 */
class PortfoliosElement extends Model {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 11/06/2016 by FI
 */
	var $validate = array(
		'portfolio_id' => array(
			'rule' => 'notEmpty',
			'message' => 'PortfoliosElement.portfolio_id'
		),
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'PortfoliosElement.name'
		)
	);
}