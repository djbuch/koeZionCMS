<?php
/**
 * Modèle permettant la gestion des boutons colonne de droite
 */
class RightButton extends Model {   

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
			'message' => 'RightButton.name'
		)
	);
}