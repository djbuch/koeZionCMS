<?php 
/**
 * Cette fonction est chargée d'initialiser les librairies nécessaires aux différentes fonctions

 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 15/01/2016 by FI
 */
function load_lib() {
			
	require_once CAKEPHP.DS.'inflector.php';
	require_once SYSTEM.DS.'router.php';
	require_once SYSTEM.DS.'object.php';
	require_once SYSTEM.DS.'model.php';
}

/**
 * Cette fonction est chargée d'initialiser la liste des templates
 * 
 * @return 	array Liste des templates
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 15/01/2016 by FI
 */
/*function init_templates() {
	
	load_lib();
	
	require_once MODELS.DS.'template.php';	
	
	$template 			= new Template();	
	$templateListTMP 	= $template->find(array('order' => 'name'));
	$templateList 		= array();
	foreach($templateListTMP as $k => $v) { $templateList[$v['id']] = $v; }
	return $templateList;	
}*/

/**
 * Cette fonction est chargée de sauvegarde les données du site Internet
 * 
 * @param	array $datas Données à sauvegarder
 * @return 	number Indique si la procédure s'est déroulée correctement
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 15/01/2016 by FI
 */
function save_website($datas) {
	
	//global $templatesList;
	
	load_lib();
	
	/////////////////////////////////////////////////
	//    ETAPE 1 : SAUVEGARDE DU SITE INTERNET    //
		require_once MODELS.DS.'website.php';
		
		$websiteModel 						= new Website();	
		//$templateId 						= $datas['template_id'];
		$template 							= $templatesList[$templateId];
		$datas['tpl_layout'] 				= $template['layout'];
		$datas['tpl_code'] 					= $template['code'];
		$datas['search_engine_position'] 	= 'header';
		$datas['created_by'] 				= 1;
		$datas['modified_by'] 				= 1;
		$datas['online'] 					= 1;
		
		$websiteModel->save($datas);
		
		define('CURRENT_WEBSITE_ID', $websiteModel->id);
	
	///////////////////////////////////////////////
	//    ETAPE 2 : SAUVEGARDE DU MENU RACINE    //
		require_once MODELS.DS.'category.php';
		$categoryModel = new Category();
		unset($categoryModel->searches_params);
		
		////////////////////////////////////////////////////////
		//   INITIALISATION DE LA CATEGORIE PARENTE DU SITE   //	
		$categorie = array(
			'parent_id' => 0,
			'type' => 3,
			'name' => 'Racine Site '.$websiteModel->id,
			'slug' => 'racine-site-'.$websiteModel->id,
			'online' => 1,
			'redirect_category_id' => 0,
			'display_form' => 0,
			'website_id' => $websiteModel->id
		);
		$categoryModel->save($categorie);
		
	return $websiteModel->id  + $categoryModel->id;	
}
