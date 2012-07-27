<?php 
//Mise en place des règles de validation
$validate = array(
	'section' => array(
		'rule1' => array(
			'rule' => array('minLength', 3),
			'message' => 'La valeur du champ est de 3 caractères minimum.'
		)
	),
	'smtp_host' => array(
		'rule1' => array(
			'rule' => array('minLength', 3),
			'message' => 'La valeur du champ est de 3 caractères minimum.'
		),
		'rule2' => array(
			'rule' => array('custom', '/^([a-zA-Z0-9-_.]+)$/'),
			'message' => "Caractères non autorisés dans le champ (Uniquement lettres, chiffres et -)."
		)
	),
	'smtp_port' => array(		
		'rule2' => array(
			'rule' => array('custom', '/^([0-9]+)$/'),
			'message' => "Caractères non autorisés dans le champ (Uniquement chiffres)."
		)
	),
	'smtp_user_name' => array(
		'rule1' => array(
			'rule' => array('minLength', 3),
			'message' => 'La valeur du champ est de 3 caractères minimum.'
		),
		'rule2' => array(
			'rule' => array('custom', '/^([a-zA-Z0-9-_@.]+)$/'),
			'message' => "Caractères non autorisés dans le champ (Uniquement lettres, chiffres et @, -, .)."
		)
	),
	'mail_set_from_email' => array(
		'rule1' => array(
			'rule' => array('minLength', 3),
			'message' => 'La valeur du champ est de 3 caractères minimum.'
		),
		'rule2' => array(
			'rule' => array('custom', '/^([a-zA-Z0-9-_@.]+)$/'),
			'message' => "Caractères non autorisés dans le champ (Uniquement lettres, chiffres et @,-, .)."
		)
	),
	'mail_set_from_name' => array(
		'rule1' => array(
			'rule' => array('minLength', 3),
			'message' => 'La valeur du champ est de 3 caractères minimum.'
		)
	),
	'bcc_email' => array(
		'rule1' => array(
			'rule' => array('minLength', 3),
			'message' => 'La valeur du champ est de 3 caractères minimum.'
		),
		'rule2' => array(
			'rule' => array('custom', '/^([a-zA-Z0-9-_@.]+)$/'),
			'message' => "Caractères non autorisés dans le champ (Uniquement lettres, chiffres et @, -, .)."
		)
	)
);

require_once(ROOT.DS.'install'.DS.'validate'.DS.'proceed.php');