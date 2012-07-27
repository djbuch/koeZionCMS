<?php 
//Mise en place des règles de validation
$validate = array(
	'name' => array(
		'rule' => array('minLength', 2),
		'message' => 'La valeur du champ est de 2 caractères minimum.'
	),
	'url' => array(
			'rule' => array('url', true),
			'message' => 'Vous devez indiquer une url valide'
	),
	'template_id' => array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez sélectionner un template'
	)
);

require_once(INSTALL_VALIDATE.DS.'proceed.php');