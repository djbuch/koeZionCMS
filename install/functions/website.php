<?php 
function load_lib() {
			
	require_once(CAKEPHP.DS.'inflector.php');
	require_once(SYSTEM.DS.'router.php');
	require_once(SYSTEM.DS.'object.php');
	require_once(SYSTEM.DS.'model.php');
}

function init_templates() {
	
	load_lib();
	
	require_once(MODELS.DS.'template.php');	
	$template = new Template();
	
	$templateListTMP = $template->find(array('order' => 'name'));
	$templateList = array();
	foreach($templateListTMP as $k => $v) { $templateList[$v['id']] = $v; }
	return $templateList;	
}

function save_website($datas) {
	
	global $templatesList;
	
	load_lib();
	
	//Etape 1 : sauvegarde du site
	require_once(MODELS.DS.'website.php');
	$websiteModel = new Website();
	$templateId = $datas['template_id'];
	$template = $templatesList[$templateId];
	$datas['tpl_layout'] = $template['layout'];
	$datas['tpl_code'] = $template['code'];
	$datas['search_engine_position'] = 'header';
	$datas['created_by'] = 1;
	$datas['modified_by'] = 1;
	$datas['online'] = 1;
	$websiteModel->save($datas);
	
	define('CURRENT_WEBSITE_ID', $websiteModel->id);
	
	//Etape 2 : sauvegarde du menu racine
	require_once(MODELS.DS.'category.php');
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
		'display_contact_form' => 0,
		'website_id' => $websiteModel->id
	);
	$categoryModel->save($categorie);
	
	return $websiteModel->id  + $categoryModel->id;	
}
