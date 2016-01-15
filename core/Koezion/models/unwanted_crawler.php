<?php
/**
 * Modèle permettant la gestion des focus
 */
class UnwantedCrawler extends Model {
	
/**
 * Tableau contenant l'ensemble des champs à valider
 * 
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 20/08/2015 by FI
 */	
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'UnwantedCrawler.name'
		)
	);
}