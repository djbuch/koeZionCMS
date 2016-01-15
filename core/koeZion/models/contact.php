<?php
/**
 * Modèle permettant la gestion des contacts
 */
class Contact extends Model {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 04/02/2012 by FI
 */
	var $validate = array(
		'name' => array(
			array(
				'rule' => array('notEqualsTo', 'Indiquez votre nom'),
				'message' => 'Contact.name'
			),
			array(
				'rule' => array('minLength', 2),
				'message' => 'Contact.name'
			)				
		),
		'phone' => array(
			array(
			'rule' => array('notEqualsTo', 'Indiquez votre téléphone'),
			'message' => 'Contact.phone'
			),
			array(
				'rule' => array('minLength', 10),
				'message' => 'Contact.phone'
			)
		),
		'email' => array(
			'rule' => 'email',
			'message' => 'Contact.email'
		),
		'cpostal' => array(
			array(
				'rule' => array('notEqualsTo', 'Indiquez votre code postal'),
				'message' => 'Contact.cpostal'
			),
			array(
				'rule' => array('minLength', 5),
				'message' => 'Contact.cpostal'
			)
		),
		'message' => array(
			array(
				'rule' => array('notEqualsTo', 'Indiquez votre message'),
				'message' => 'Contact.message'
			),
			array(
				'rule' => array('minLength', 10),
				'message' => 'Contact.message'
			)
		)
	);
}