<?php
$contentPage = $this->vars['components']['Text']->format_content_text($category['content']);
echo $contentPage;
if($category['display_form']) { $this->element('forms/contact'); }

if(isset($portfolios) && !empty($portfolios)) { $this->element('portfolios/list'); }
else {
	
	if($rightColumn) { $postClass = 'col-xs-12 col-md-6'; } //Si il y a une colonne on affichera que 2 éléments par ligne
	else { $postClass = 'col-xs-12 col-sm-6 col-md-4'; } //Si il n'y a pas de colonne on affichera 3 éléments par ligne
	$this->element('posts/list', array('container' => false, 'postClass' => $postClass));
}
$this->element('pagination');