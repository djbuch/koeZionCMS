<?php 
$this->element('breadcrumbs');
$title_for_layout = $category['name'];
$description_for_layout = $category['page_description'];
$keywords_for_layout = $category['page_keywords'];
?>
<div class="container_omega">
	<?php 
	echo $category['txt_secure']; 
	$this->element('formulaire_page_secure');	
	?>
	<div class="clearfix"></div>
</div>