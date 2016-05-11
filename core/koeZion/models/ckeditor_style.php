<?php
/**
 * Modèle permettant la gestion des styles CK Editor
 */
class CkeditorStyle extends Model {   

///////////////////	
//   VARIABLES   //	
///////////////////
		
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 10/05/2016 by FI
 */	
	var $validate = array(
		'layout' => array(
			'rule' => 'notEmpty',
			'message' => 'CkeditorStyle.layout'
		),
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'CkeditorStyle.name'
		),
		'element' => array(
			'rule' => 'notEmpty',
			'message' => 'CkeditorStyle.element'
		)
	);
}