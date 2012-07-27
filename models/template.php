<?php
/**
 * Modèle permettant la gestion des templates
 */
class Template extends Model {
	
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */		
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'Color.name'
		),
		'layout' => array(
			'rule' => array('minLength', 2),
			'message' => 'Color.layout'
		),
		'code' => array(
			'rule' => array('minLength', 2),
			'message' => 'Color.code'
		),
		'color' => array(
			'rule' => array('minLength', 6),
			'message' => 'Color.color'
		)
	);	
}