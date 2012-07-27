<?php
/**
 * Modèle permettant la gestion des focus
 */
class Focus extends Model {   

/**
 * Nom de la table à utiliser
 * 
 * @var 	varchar
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI 
 */	
	var $table = 'focus';
	
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
			'message' => 'Focus.name'
		)
	);
}