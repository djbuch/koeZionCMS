<?php 
$this->element('breadcrumbs');
$title_for_layout = $post['name'];
$description_for_layout = $post['page_description'];
$keywords_for_layout = $post['page_keywords'];
?>
<div class="container_omega">
	<?php 
	echo $post['txt_secure']; 
	$this->element('formulaire_page_secure');	
	?>
	<div class="clearfix"></div>
</div>