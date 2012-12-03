<?php
/**
 * Modèle permettant la gestion des commentaires sur les articles
 */
class PostsComment extends Model {   

/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/02/2012 by FI
 */
	var $validate = array(
		'name' => array(
			'rule' => array('notEqualsTo', 'Indiquez votre nom'),
			'message' => 'PostsComment.name'				
		),
		'email' => array(
			'rule' => 'email',
			'message' => 'PostsComment.email'
		),
		'cpostal' => array(
			'rule' => array('notEqualsTo', 'Indiquez votre code postal'),
			'message' => 'PostsComment.cpostal'
		),
		'message' => array(
			'rule' => array('notEqualsTo', 'Indiquez votre message'),
			'message' => 'PostsComment.message'
		),
	);
}