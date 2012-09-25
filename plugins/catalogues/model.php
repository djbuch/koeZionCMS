<?php
/**
 * Modèle permettant la gestion de l'ensemble des produits
 */
class Catalogue extends Model {   
	
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */
	var $validate = array(
		'category_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez indiquer la page parente.'
		),			
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'La valeur de ce champ est de 2 caractères minimum.'
		)
	);
}