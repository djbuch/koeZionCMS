<?php
$aUrlList = array();

//Réorganisation de la liste des cétégories
foreach($categories as $k => $v) { 
	
	$aUrlList['[ ==== '._("Pages catégories").' ==== ]'] = ''; 
	$url = Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']);	
	if(isset($v['redirect_to']) && !empty($v['redirect_to'])) { $url = Router::url($v['redirect_to']); }
	$aUrlList[str_repeat('___', $v['level'] + 1).$v['name']] = $url;
}

//Réorganisation de la liste des articles
foreach($posts as $k => $v) {

	$aUrlList['[ ==== '._("Articles").' ==== ]'] = '';
	$url = Router::url('posts/view/id:'.$v['id'].'/slug:'.$v['slug'].'/prefix:'.$v['prefix']);
	if(isset($v['redirect_to']) && !empty($v['redirect_to'])) { $url = Router::url($v['redirect_to']); }
	$aUrlList['___'.$v['name']] = $url;
}

/////////////////////////////////////////////////////////////////////////////
//   REGLES ADDITIONNELLES POUR LA generation DES LIENS POUR LES PLUGINS   //
$moreLinks = CONFIGS.DS.'plugins'.DS.'ckeditor'.DS.'generate_links';
if(is_dir($moreLinks)) {

	foreach(FileAndDir::directoryContent($moreLinks) as $moreLink) { require_once($moreLinks.DS.$moreLink); }
}
/////////////////////////////////////////////////////////////////////////////

/*if(isset($flipbooks) && !empty($flipbooks)) {
	
	foreach($flipbooks as $k => $v) {
	
		$aUrlList['[ ==== '._("Flipbooks").' ==== ]'] = '';
		$aUrlList['___'.$v['name']] = Router::url('flipbooks/index/id:'.$v['id'].'/slug:'.$v['slug']);
	}
}*/

/*
//Réorganisation de la liste des types d'articles
foreach($postsTypes as $k => $v) {

	$aUrlList["[ ==== "._("Types d'articles")." ==== ]"] = '';	
	$aUrlList['___'.$v['name']] = Router::url('posts/listing').'?typepost='.$v['id'];
}

//Réorganisation de la liste des rédacteurs
foreach($writers as $k => $v) {

	$aUrlList["[ ==== "._("Rédacteurs")." ==== ]"] = '';	
	$aUrlList['___'.$v] = Router::url('posts/listing').'?writer='.$k;
}

//Réorganisation de la liste des dates de publication
foreach($publicationDates as $k => $v) {
	
	$postDate = $this->vars['components']['Text']->date_sql_to_human($v['publication_date']);
	$aUrlList["[ ==== "._("Dates de publication")." ==== ]"] = '';
	$aUrlList['___'.$postDate['txt']] = Router::url('posts/listing').'?date='.$postDate['sql'];
}
*/

$aUrlList["[ ==== "._("Page d'accueil")." ==== ]"] = Router::url('/');
$aUrlList["[ ==== "._("Newsletter")." ==== ]"] = Router::url('contacts/newsletter');

if(!function_exists('json_encode')) {

	//utilisation d'un package PEAR
	include(PEAR.DS.'JSON.php');
	$oJson = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	$sContenuPage = $oJson->encode($aUrlList);
	unset($oJson);
} else {

	$sContenuPage = $helpers['Json']->encode($aUrlList);
}

echo $sContenuPage;
?>