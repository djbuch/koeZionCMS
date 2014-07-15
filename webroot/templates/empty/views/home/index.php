<?php 
$title_for_layout 		= $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout 	= $websiteParams['seo_page_keywords'];
?><div id="home" class="index"><?php 

$this->element('slider'); 

if(isset($websiteParams['txt_slogan'])) { ?><div class="slogan"><?php echo $websiteParams['txt_slogan']; ?></div><?php }

$this->element('focus');

if(count($posts) > 0) {

	if(isset($websiteParams['txt_posts'])) { ?><div><?php echo $websiteParams['txt_posts']; ?></div><?php }	
	?><div class="posts_list"><?php $this->element('posts_list'); ?></div><?php 
}
?></div>