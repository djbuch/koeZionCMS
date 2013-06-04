<?php 
$title_for_layout = $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout = $websiteParams['seo_page_keywords'];
 
$this->element('slider'); 

if(isset($websiteParams['txt_slogan'])) { ?><div class="container_gamma slogan"><?php echo $websiteParams['txt_slogan']; ?></div><?php }

$this->element('focus');

if(count($posts) > 0) {

	if(isset($websiteParams['txt_posts'])) { ?><div class="container_gamma slogan"><?php echo $websiteParams['txt_posts']; ?></div><?php }	
	?>
	<div class="container_omega">	
		<?php $this->element('posts_list', array('cssZone' => 'gs_12', 'displayPosts' => 1)); ?>
		<?php //$this->element('colonne_droite'); ?>
	</div>
	<?php 
}
?>