<?php
/**
 * Modèle permettant la gestion des types d'utilisateurs
 */
class UsersGroup extends Model {   

///////////////////	
//   VARIABLES   //	
///////////////////
		
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */	
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'UsersGroup.name'
		)
	);
}