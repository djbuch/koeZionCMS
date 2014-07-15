<?php
$this->element('breadcrumbs');
$title_for_layout 		= $category['page_title'];
$description_for_layout = $category['page_description'];
$keywords_for_layout 	= $category['page_keywords'];

$contentPage = $this->vars['components']['Text']->format_content_text($category['content']);
?>
<div id="categories" class="view category<?php echo $category['id']; ?>">
	<?php	
	if(count($children) == 0 && count($brothers) == 0 && count($postsTypes) == 0 && count($rightButtons) == 0) { 
		
		echo $contentPage;		
		if($category['display_form']) { $this->element('formulaires/formulaire_contact'); }
		
	} else { 
		
		$this->element('column'); 

		echo $contentPage;
		if($category['display_form']) { $this->element('formulaires/formulaire_contact'); }		
		$this->element('posts_list'); 		 
	}
	?>
</div>