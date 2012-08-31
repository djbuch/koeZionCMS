<?php
/**
 * Modèle permettant la gestion des plugins
 */
class Plugin extends Model {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 31/08/2012 by FI
 */
	var $validate = array(
		'name' => array(
			'rule1' => array(
				'rule' => array('minLength', 5),
				'message' => 'Plugin.code'
			),
			'rule2' => array(
				'rule' => array('maxLength', 5),
				'message' => 'Plugin.code'
			)
		),
		'description' => array(
			'rule' => array('minLength', 2),
			'message' => 'Plugin.name'
		)
	);
}