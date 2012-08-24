<?php
/**
 * Modèle permettant la gestion des sliders
 */
class Slider extends Model {   

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
			'message' => 'Slider.name'
		)
	);	
	
/**
 * Tableau contenant l'ensemble des champs à uploader
 * Rajouter en BDD les champs (varchar(255)):
 * - img_name
 * - img_path
 * 
 * Ces champs servent à stocker la valeur du nom et du dossier de l'image
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @deprecated since 24/08/2012
 */
	//var $files_to_upload = array('img' => array('bdd' => true));
}