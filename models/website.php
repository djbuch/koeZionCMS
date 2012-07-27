<?php
/**
 * Modèle permettant la gestion des sites Internet
 */
class Website extends Model {
	
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 30/04/2012 by FI
 */		
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'Website.name'
		),
		'url' => array(
			'rule' => array('url', true),
			'message' => 'Website.url'
		),
		'template_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Website.template'
		)
	);	
}