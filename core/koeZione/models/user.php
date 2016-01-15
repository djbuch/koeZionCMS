<?php
/**
 * Modèle permettant la gestion des utilisateurs de l'application
 */
class User extends Model {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 05/06/2012 by FI
 */
	var $validate = array(
		/*'role' => array(
			'rule' => 'notEmpty',
			'message' => 'User.role'
		);*/
		'users_group_id' => array(
			'rule' => 'notEmpty',
			'message' => 'User.group'
		),
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'User.name'
		),
		'login' => array(
			'rule' => array('minLength', 4),
			'message' => 'User.login'
		),
		'password' => array(
			'rule' => array('minLength', 4),
			'message' => 'User.password'
		),
		'email' => array(
			'rule1' => array(
				'rule' => 'email',
				'message' => 'User.email1'
			),
			'rule2' => array(
				'rule' => array('callback', array('only_one_email')),
				'message' => 'User.email2',
			)
		)			
	);
}