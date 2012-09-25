<?php
/**
 * Modèle permettant la gestion de l'ensemble des formulaire
 */
class Formulaire extends Model {   
	
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/09/2012 by FI
 */
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'La valeur de ce champ est de 2 caractères minimum.'
		)
	);	
	
/**
 * Tableau contenant l'ensemble des champs à uploader
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/09/2012 by FI
 */
	var $files_to_upload = array(
		'form_file' => array(
			'bdd' => true,
			'path' => CONFIGS_FORMS
		)
	);
}