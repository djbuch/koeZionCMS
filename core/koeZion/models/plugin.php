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
 * @version 0.1 - 06/05/2016 by FI
 */
	var $validate = array(
		'code' => array(
			'rule' => array('minLength', 5),
			'message' => 'Plugin.code'
		),
		'name' => array(
			'rule' => array('minLength', 5),
			'message' => 'Plugin.code'
		),
		'description' => array(
			'rule' => array('minLength', 2),
			'message' => 'Plugin.description'
		),
		'author' => array(
			'rule' => array('minLength', 2),
			'message' => 'Plugin.author'
		)
	);
}